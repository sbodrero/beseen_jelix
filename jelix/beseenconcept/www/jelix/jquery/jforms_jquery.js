/**
* @package    jelix
* @subpackage forms
* @author     Laurent Jouanneau
* @contributor  Julien Issler, Dominique Papin
* @copyright   2007-2008 Laurent Jouanneau
* @copyright    2008 Julien Issler, 2008 Dominique Papin
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/*
usage :

jFormsJQ.tForm = new jFormsJQForm('name');                         // create a form descriptor
jFormsJQ.tForm.setErrorDecorator(new jFormsJQErrorDecoratorAlert());    // declare an error handler

// declare a form control
var c = new jFormsJQControl('name', 'a label', 'datatype');
c.required = true;
c.errInvalid='';
c.errRequired='';
jFormsJQ.tForm.addControl(c);
...

// declare the form now. A 'submit" event handler will be attached to the corresponding form element
jFormsJQ.declareForm(jFormsJQ.tForm);

*/

/**
 * form manager
 */
var jFormsJQ = {
    _forms: {},

    declareForm : function(aForm){
        this._forms[aForm.name]=aForm;
        jQuery('#'+aForm.name).bind('submit',function (ev) {
            //if( aForm.submitted ) {
                //do not submit twice !
            //    return false;
            //}
            //aForm.submitted = true;
            jQuery(ev.target).trigger('jFormsUpdateFields');
            return jFormsJQ.verifyForm(ev.target) });
    },

    getForm : function (name) {
        return this._forms[name];
    },

    verifyForm : function(frmElt){
        var tForm = this._forms[frmElt.attributes.getNamedItem("id").value]; // we cannot use getAttribute for id because a bug with IE
        var msg = '';
        var valid = true;
        tForm.errorDecorator.start();
        for(var i =0; i < tForm.controls.length; i++){
            if (!this.verifyControl(tForm.controls[i], tForm, valid)) {
                valid = false;
            }
        }
        if(!valid) {
            tForm.errorDecorator.end();
        }
        tForm.valid = valid ;
        return valid;
    },

    /**
     * @param jFormsJQControl*  ctrl     a jform control
     * @param jFormsJQForm      frm      the jform object
     */
    verifyControl : function (ctrl, frm, scrollIfError) {
        var val;
        if(typeof ctrl.getValue == 'function') {
            val = ctrl.getValue();
        }
        else {
            var elt = frm.element.elements[ctrl.name];
            if (!elt) return true; // sometimes, all controls are not generated...
            val = this.getValue(elt);
        }

        if (val === null || val === false) {
            if (ctrl.required) {
                frm.errorDecorator.addError(ctrl, 1, '', scrollIfError);
                return false;
            }
        }
        else {
            if(!ctrl.check(val, frm)){
                frm.errorDecorator.addError(ctrl, 2, '', scrollIfError);
                return false;
            } else if( frm.jsonFieldCheckerUrl && frm.jsonCheckedFields && frm.jsonCheckedFields.indexOf(","+ctrl.name+",") >= 0 ) {
                var jsonCtrlValid = true;
                jQuery.ajax({ url:frm.jsonFieldCheckerUrl, 
                              data: { fieldName: ctrl.name, fieldValue: val},
                              type: 'POST',
                              async: false,
                              dataType: 'json',
                              success: function(data) {
                                    if( data && ! data.fieldOk ) {
                                        frm.valid = false;
                                        frm.errorDecorator.addError(ctrl, 3, data.errorMsg, scrollIfError);
                                        jsonCtrlValid = false;
                                    } else {
                                        if( ctrl.required ) {
                                            frm.errorDecorator.addOk(ctrl);
                                        } else {
                                            frm.errorDecorator.removeError(ctrl);
                                        }
                                    }                              
                              }
                         });
                return jsonCtrlValid;
            }
        }
        if( ctrl.required ) {
                //frm.errorDecorator.removeError(ctrl);
                frm.errorDecorator.addOk(ctrl);
        } else {
                frm.errorDecorator.removeError(ctrl);
        }
        return true;
    },

    getValue : function (elt){
        if(elt.nodeType) { // this is a node
            switch (elt.nodeName.toLowerCase()) {
                case "input":
                    if(elt.getAttribute('type') == 'checkbox')
                        return elt.checked;
                case "textarea":
                    var val = jQuery.trim(elt.value);
                    return (val !== '' ? val:null);
                case "select":
                    if (!elt.multiple)
                        return (elt.value!==''?elt.value:null);
                    var values = [];
                    for (var i = 0; i < elt.options.length; i++) {
                        if (elt.options[i].selected)
                            values.push(elt.options[i].value);
                    }
                    return (values.length>0 ? values : null);
            }
        } else if(this.isCollection(elt)){
            // this is a NodeList of radio buttons or multiple checkboxes
            var values = [];
            for (var i = 0; i < elt.length; i++) {
                var item = elt[i];
                if (item.checked)
                    values.push(item.value);
            }
            if(values.length) {
                if (elt[0].getAttribute('type') == 'radio')
                    return values[0];
                return values;
            }
        }
        return null;
    },

    showHelp : function(aFormName, aControlName){
        var frm = this._forms[aFormName];
        var ctrls = frm.controls;
        var ctrl = null;
        for(var i=0; i < ctrls.length; i++){
            if (ctrls[i].name == aControlName) {
                ctrl = ctrls[i];
                break;
            }
            if (ctrls[i].confirmField &&  ctrls[i].confirmField.name == aControlName) {
                ctrl = ctrls[i].confirmField;
                break;
            }
        }
        if (ctrl) {
            frm.helpDecorator.show(ctrl.help, ctrl);
        }
    },

    hideHelp : function(aFormName, aControlName){
        var frm = this._forms[aFormName];
        var ctrls = frm.controls;
        var ctrl = null;
        for(var i=0; i < ctrls.length; i++){
            if (ctrls[i].name == aControlName) {
                ctrl = ctrls[i];
                break;
            }
            if (ctrls[i].confirmField &&  ctrls[i].confirmField.name == aControlName) {
                ctrl = ctrls[i].confirmField;
                break;
            }
        }
        if (ctrl) {
            frm.helpDecorator.hide(ctrl);
        }
    },

    hasClass: function (elt,clss) {
        return elt.className.match(new RegExp('(\\s|^)'+clss+'(\\s|$)'));
    },
    addClass: function (elt,clss) {
        if (this.isCollection(elt)) {
            for(var j=0; j<elt.length;j++) {
                if (!this.hasClass(elt[j],clss)) {
                    elt[j].className += " "+clss;
                }
            }
        } else {
            if (!this.hasClass(elt,clss)) {
                elt.className += " "+clss;
            }
        }
    },
    removeClass: function (elt,clss) {
        if (this.isCollection(elt)) {
            for(var j=0; j<elt.length;j++) {
                if (this.hasClass(elt[j],clss)) {
                    elt[j].className = elt[j].className.replace(new RegExp('(\\s|^)'+clss+'(\\s|$)'),' ');
                }
            }
        } else {
            if (this.hasClass(elt,clss)) {
                elt.className = elt.className.replace(new RegExp('(\\s|^)'+clss+'(\\s|$)'),' ');
            }
        }
    },
    setAttribute: function(elt, name, value){
        if (this.isCollection(elt)) {
            for(var j=0; j<elt.length;j++) {
                elt[j].setAttribute(name, value);
            }
        } else {
            elt.setAttribute(name, value);
        }
    },
    removeAttribute: function(elt, name){
        if (this.isCollection(elt)) {
            for(var j=0; j<elt.length;j++) {
                elt[j].removeAttribute(name);
            }
        } else {
            elt.removeAttribute(name);
        }
    },
    isCollection: function(elt) {
        if (typeof HTMLCollection != "undefined" && elt instanceof HTMLCollection) {
            return true;
        }
        if (typeof NodeList != "undefined" && elt instanceof NodeList) {
          return true;
        }
        if (elt instanceof Array)
            return true;
        if (elt.length != undefined && (elt.localName == undefined || elt.localName == 'SELECT' || elt.localName != 'select'))
            return true;
        return false;
    }
};

/**
 * represents a form
 */
function jFormsJQForm(name){
    this.name = name;
    this.controls = [];
    this.errorDecorator =  new AppErrorDecorator();
    this.helpDecorator =  new AppErrorDecorator();
    this.element = jQuery('#'+name).get(0);
    this.submitted = false;
};

jFormsJQForm.prototype={

    valid: false,

    addControl : function(ctrl){
        this.controls.push(ctrl);
        ctrl.formName = this.name;
    },

    setErrorDecorator : function (decorator){
        this.errorDecorator = decorator;
        if (this.errorDecorator.setForm)
            this.errorDecorator.setForm(this);
    },

    setHelpDecorator : function (decorator){
        this.helpDecorator = decorator;
        if (this.helpDecorator.setForm)
            this.helpDecorator.setForm(this);
    },

    setJsonFieldCheckerUrl : function (url){
        this.jsonFieldCheckerUrl = url;
    },

    setJsonCheckedFields : function (fieldList){
        this.jsonCheckedFields = ","+fieldList.replace(/\s/i, "")+",";
    },

    getControl : function(aControlName) {
        var ctrls = this.controls;
        for(var i=0; i < ctrls.length; i++){
            if (ctrls[i].name == aControlName) {
                return ctrls[i];
            }
        }
        return null;
    }
};

/**
 * control with string
 */
function jFormsJQControlString(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help = help;
    this.minLength = -1;
    this.maxLength = -1;
};
jFormsJQControlString.prototype.check = function (val, jfrm) {
    if(this.minLength != -1 && val.length < this.minLength)
        return false;
    if(this.maxLength != -1 && val.length > this.maxLength)
        return false;
    return true;
};

/**
 * control for secret input
 */
function jFormsJQControlSecret(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help = help;
    this.minLength = -1;
    this.maxLength = -1;
};
jFormsJQControlSecret.prototype.check = function (val, jfrm) {
    if(this.minLength != -1 && val.length < this.minLength)
        return false;
    if(this.maxLength != -1 && val.length > this.maxLength)
        return false;
    return true;
};

/**
 * confirm control
 */
function jFormsJQControlConfirm(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help = help;
    this._masterControl = name.replace(/_confirm$/,'');
};
jFormsJQControlConfirm.prototype.check = function(val, jfrm) {
    if(jFormsJQ.getValue(jfrm.element.elements[this._masterControl]) !== val)
        return false;
    return true;
};

/**
 * control with boolean
 */
function jFormsJQControlBoolean(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlBoolean.prototype.check = function (val, jfrm) {
    return (val == true || val == false);
};

/**
 * control with Decimal
 */
function jFormsJQControlDecimal(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlDecimal.prototype.check = function (val, jfrm) {
    return ( -1 != val.search(/^\s*[\+\-]?\d+(\.\d+)?\s*$/));
};

/**
 * control with Integer
 */
function jFormsJQControlInteger(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlInteger.prototype.check = function (val, jfrm) {
    return ( -1 != val.search(/^\s*[\+\-]?\d+\s*$/));
};

/**
 * control with Hexadecimal
 */
function jFormsJQControlHexadecimal(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlHexadecimal.prototype.check = function (val, jfrm) {
  return (val.search(/^0x[a-f0-9A-F]+$/) != -1);
};

/**
 * control with Datetime
 */
function jFormsJQControlDatetime(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
    this.minDate = null;
    this.maxDate = null;
    this.multiFields = false;
};
jFormsJQControlDatetime.prototype.check = function (val, jfrm) {
    var t = val.match(/^(\d{4})\-(\d{2})\-(\d{2}) (\d{2}):(\d{2})(:(\d{2}))?$/);
    if(t == null) return false;
    var yy = parseInt(t[1],10);
    var mm = parseInt(t[2],10) -1;
    var dd = parseInt(t[3],10);
    var th = parseInt(t[4],10);
    var tm = parseInt(t[5],10);
    var ts = 0;
    if(t[7] != null && t[7] != "")
        ts = parseInt(t[7],10);
    var dt = new Date(yy,mm,dd,th,tm,ts);
    if(yy != dt.getFullYear() || mm != dt.getMonth() || dd != dt.getDate() || th != dt.getHours() || tm != dt.getMinutes() || ts != dt.getSeconds())
        return false;
    else if((this.minDate !== null && val < this.minDate) || (this.maxDate !== null && val > this.maxDate))
        return false;
    return true;
};
jFormsJQControlDatetime.prototype.getValue = function(){
    if (!this.multiFields) {
        var val = jQuery.trim(jQuery('#'+this.formName+'_'+this.name).val());
        return (val!==''?val:null);
    }

    var controlId = '#'+this.formName+'_'+this.name;
    var v = jQuery(controlId+'_year').val() + '-'
        + jQuery(controlId+'_month').val() + '-'
        + jQuery(controlId+'_day').val() + ' '
        + jQuery(controlId+'_hour').val() + ':'
        + jQuery(controlId+'_minutes').val();

    var secondsControl = jQuery('#'+this.formName+'_'+this.name+'_seconds');
    if(secondsControl.attr('type') !== 'hidden'){
        v += ':'+secondsControl.val();
        if(v == '-- ::')
            return null;
    }
    else if(v == '-- :')
        return null;
    return v;
};

/**
 * control with Date
 */
function jFormsJQControlDate(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
    this.multiFields = false;
    this.minDate = null;
    this.maxDate = null;
};
jFormsJQControlDate.prototype.check = function (val, jfrm) {
    var t = val.match(/^(\d{4})\-(\d{2})\-(\d{2})$/);
    if(t == null) return false;
    var yy = parseInt(t[1],10);
    var mm = parseInt(t[2],10) -1;
    var dd = parseInt(t[3],10);
    var dt = new Date(yy,mm,dd,0,0,0);
    if(yy != dt.getFullYear() || mm != dt.getMonth() || dd != dt.getDate())
        return false;
    else if((this.minDate !== null && val < this.minDate) || (this.maxDate !== null && val > this.maxDate))
        return false;
    return true;
};
jFormsJQControlDate.prototype.getValue = function(){
    if (!this.multiFields) {
        var val = jQuery.trim(jQuery('#'+this.formName+'_'+this.name).val());
        return (val!==''?val:null);
    }

    var controlId = '#'+this.formName+'_'+this.name;
    var v = jQuery(controlId+'_year').val() + '-'
        + jQuery(controlId+'_month').val() + '-'
        + jQuery(controlId+'_day').val();
    if(v == '--')
        return null;
    return v;
};

/**
 * control with time
 */
function jFormsJQControlTime(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlTime.prototype.check = function (val, jfrm) {
    var t = val.match(/^(\d{2}):(\d{2})(:(\d{2}))?$/);
    if(t == null) return false;
    var th = parseInt(t[1],10);
    var tm = parseInt(t[2],10);
    var ts = 0;
    if(t[4] != null)
        ts = parseInt(t[4],10);
    var dt = new Date(2007,05,02,th,tm,ts);
    if(th != dt.getHours() || tm != dt.getMinutes() || ts != dt.getSeconds())
        return false;
    else
        return true;
};

/**
 * control with LocaleDateTime
 */
function jFormsJQControlLocaleDatetime(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
    this.lang='';
};
jFormsJQControlLocaleDatetime.prototype.check = function (val, jfrm) {
    var yy, mm, dd, th, tm, ts;
    if(this.lang.indexOf('fr_') == 0) {
        var t = val.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})(:(\d{2}))?$/);
        if(t == null) return false;
        yy = parseInt(t[3],10);
        mm = parseInt(t[2],10) -1;
        dd = parseInt(t[1],10);
        th = parseInt(t[4],10);
        tm = parseInt(t[5],10);
        ts = 0;
        if(t[7] != null)
            ts = parseInt(t[7],10);
    }else{
        //default is en_* format
        var t = val.match(/^(\d{2})\/(\d{2})\/(\d{4}) (\d{2}):(\d{2})(:(\d{2}))?$/);
        if(t == null) return false;
        yy = parseInt(t[3],10);
        mm = parseInt(t[1],10) -1;
        dd = parseInt(t[2],10);
        th = parseInt(t[4],10);
        tm = parseInt(t[5],10);
        ts = 0;
        if(t[7] != null)
            ts = parseInt(t[7],10);
    }
    var dt = new Date(yy,mm,dd,th,tm,ts);
    if(yy != dt.getFullYear() || mm != dt.getMonth() || dd != dt.getDate() || th != dt.getHours() || tm != dt.getMinutes() || ts != dt.getSeconds())
        return false;
    else
        return true;
};

/**
 * control with localedate
 */
function jFormsJQControlLocaleDate(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
    this.lang='';
};
jFormsJQControlLocaleDate.prototype.check = function (val, jfrm) {
    var yy, mm, dd;
    if(this.lang.indexOf('fr_') == 0) {
        var t = val.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
        if(t == null) return false;
        yy = parseInt(t[3],10);
        mm = parseInt(t[2],10) -1;
        dd = parseInt(t[1],10);
    }else{
        //default is en_* format
        var t = val.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
        if(t == null) return false;
        yy = parseInt(t[3],10);
        mm = parseInt(t[1],10) -1;
        dd = parseInt(t[2],10);
    }
    var dt = new Date(yy,mm,dd,0,0,0);
    if(yy != dt.getFullYear() || mm != dt.getMonth() || dd != dt.getDate())
        return false;
    else
        return true;
};

/**
 * control with Url
 */
function jFormsJQControlUrl(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlUrl.prototype.check = function (val, jfrm) {
    return (val.search(/^[a-z]+:\/\/((((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))((\/)|$)/) != -1);
};

/**
 * control with email
 */
function jFormsJQControlEmail(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlEmail.prototype.check = function (val, jfrm) {
    return (val.search(/^((\"[^\"f\n\r\t\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/) != -1);
};


/**
 * control with ipv4
 */
function jFormsJQControlIpv4(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlIpv4.prototype.check = function (val, jfrm) {
    var t = val.match(/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/);
    if(t)
        return (t[1] < 256 && t[2] < 256 && t[3] < 256 && t[4] < 256);
    return false;
};

/**
 * control with ipv6
 */
function jFormsJQControlIpv6(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
};
jFormsJQControlIpv6.prototype.check = function (val, jfrm) {
    return (val.search(/^([a-f0-9]{1,4})(:([a-f0-9]{1,4})){7}$/i) != -1);
};

/**
 * choice control
 */
function jFormsJQControlChoice(name, label, help) {
    this.name = name;
    this.label = label;
    this.required = false;
    this.errInvalid = '';
    this.errRequired = '';
    this.help=help;
    this.items = {};
};
jFormsJQControlChoice.prototype = {
    addControl : function (ctrl, itemValue) {
        if(this.items[itemValue] == undefined)
            this.items[itemValue] = [];
        this.items[itemValue].push(ctrl);
    },
    check : function (val, jfrm) {
        if(this.items[val] == undefined)
            return false;

        var list = this.items[val];
        var valid = true;
        for(var i=0; i < list.length; i++) {
            var val2 = jFormsJQ.getValue(jfrm.element.elements[list[i].name]);

            if (val2 == '') {
                if (list[i].required) {
                    jfrm.errorDecorator.addError(list[i], 1);
                    valid = false;
                }
            } else if (!list[i].check(val2, jfrm)) {
                jfrm.errorDecorator.addError(list[i], 2);
                valid = false;
            }
        }
        return valid;
    },
    activate : function (val) {
        var frmElt = document.getElementById(this.formName);
        for(var j in this.items) {
            var list = this.items[j];
            for(var i=0; i < list.length; i++) {
                var elt = frmElt.elements[list[i].name];
                if (val == j) {
                    jFormsJQ.removeAttribute(elt, "readonly");
                    jFormsJQ.removeClass(elt, "jforms-readonly");
                } else {
                    jFormsJQ.setAttribute(elt, "readonly", "readonly");
                    jFormsJQ.addClass(elt, "jforms-readonly");
                }
            }
        }
    }
};


/**
 * Decorator to display help messages in an floating dialog box
 */
function jFormsJQHelpDecoratorFloating() {
    this.form = null;

};
jFormsJQHelpDecoratorFloating.prototype = {
  setForm : function(aForm) {
    this.form = aForm;
  },
    show : function( message, control ){
      var selectorCtl = '#'+this.form.name+'_'+control.name;
      selectorCtl = selectorCtl.replace(/\[\]/g, '');//remove square brackets
      if( $(selectorCtl).length == 0 ) {
          selectorCtl = 'form#'+this.form.name+' span.jforms-ctl-'+control.name.replace(/\[\]/g, ''); //for radios, date, ...
      }
      if( message ) {
          if( $(selectorCtl).siblings().filter('span.controlErrorMsg').length > 0 ) {
              message = '<span class="controlErrorMsg">' + $(selectorCtl).siblings().filter('span.controlErrorMsg').html() + "</span>" + message;
          }
          if( $('div.helpDecorator' + control.name.replace(/\[\]/g, '')).length == 0 ) {
              var helpPosition = $(selectorCtl).closest('.userFormItem').children(':visible').last().position();
              helpPosition.top = $(selectorCtl).closest('.userFormItem').children(':visible').first().position().top-5;
              helpPosition.left += $(selectorCtl).closest('.userFormItem').children(':visible').last().outerWidth() + 15;
              var appendedHelp = '<div style="display: none;" class="helpDecorator helpDecorator' + control.name.replace(/\[\]/g, '') + '"><span class="helpDecoratorHead"></span><span class="helpDecoratorContent">' + message + '</span><span class="helpDecoratorFoot"></span></div>';
              $(selectorCtl).closest('.userFormItem').append(appendedHelp).children('div.helpDecorator' + control.name.replace(/\[\]/g, '')).css({'position': 'absolute', 'left': helpPosition.left, 'top': helpPosition.top}).stop(true,false).fadeTo(400,1);
          } else {
              //update content
              $('div.helpDecorator' + control.name.replace(/\[\]/g, '')).stop(true,false).fadeTo(400,1).html('<span class="helpDecoratorHead"></span><span class="helpDecoratorContent">' + message + '</span><span class="helpDecoratorFoot"></span>');
          }
      }
    },
    hide : function( control ){
      var selector = '#'+this.form.name+'_'+control.name;
      selector = selector.replace(/\[\]/g, '');//remove square brackets
      if( $(selector).length == 0 ) {
          selector = 'form#'+this.form.name+' span.jforms-ctl-'+control.name.replace(/\[\]/g, ''); //for radios, date, ...
      }
      $('div.helpDecorator' + control.name.replace(/\[\]/g, '')).stop(true,false).fadeOut(400,function() {
              $(this).remove();
              });
    }

};



// application specific error decorator
function AppErrorDecorator(){
  this.errorList = null;
  this.form = null;
}

AppErrorDecorator.prototype = {
  setForm : function(aForm) {
    this.form = aForm;
  },
  start : function(){
    if ($('ul.jforms-error-list').length == 0)
      $('#'+this.form.name+' script:first').after('<ul class="jforms-error-list"/>');
    this.errorList = $('ul.jforms-error-list');
    $(this.errorList).hide();
    $(this.errorList).html('');
    $('.jforms-error').removeClass('jforms-error');
  },
  addError : function(control, messageType, messageArg, scrollFlag){
      var message='';
      if(messageType == 1){
          message = control.errRequired;
      }else if(messageType == 2){
          message = control.errInvalid;
      }else if(messageType == 3){
          message = messageArg;
      }else{
          message = "Error on '"+control.label+"' field";
      }
      var selector = '#'+this.form.name+'_'+control.name;
      selector = selector.replace(/\[\]/g, '');//remove square brackets
      if( $(selector).length == 0 ) {
          selector = 'form#'+this.form.name+' span.jforms-ctl-'+control.name; //for radios, date, ...
          selector = selector.replace(/\[\]/g, '');//remove square brackets
      }
      $(selector).addClass('jforms-error').siblings('.jforms-label').addClass('jforms-error');

      if( $(selector).next().filter('span.controlHelper').length != 0 ) {
          $(selector).next().filter('span.controlHelper').removeClass('controlOk').addClass('controlError');
      } else if( $(selector).parent().next().filter('span.controlHelper').length != 0 ) {//dirty hack if controls are in a div or something (e.g. usefull for checkboxes)
          $(selector).parent().next().filter('span.controlHelper').removeClass('controlOk').addClass('controlError');
      } else {
          $(selector).after('<span class="controlHelper">*</span>');
          $(selector).next().filter('span.controlHelper').removeClass('controlOk').addClass('controlError');
      }

      if( $(selector).siblings().filter('span.controlErrorMsg').length == 0 ) {
          $(selector).before('<span class="controlErrorMsg"></span>')
      }
      $(selector).siblings().filter('span.controlErrorMsg').html(message);
      if( scrollFlag ) {
          if( $(selector).length > 0 && $(selector+':in-viewport').length == 0 ) {
                $.scrollTo(selector, 500, {offset:-10});
          }
          $('input[type=text]').not(selector).blur();
          jFormsJQ.showHelp(this.form.name, control.name);
          $(selector).focus();
      }
  },
  removeError : function(control){
      var selector = '#'+this.form.name+'_'+control.name;
      selector = selector.replace(/\[\]/g, '');//remove square brackets
      if( $(selector).length == 0 ) {
          selector = 'form#'+this.form.name+' span.jforms-ctl-'+control.name; //for radios, date, ...
          selector = selector.replace(/\[\]/g, '');//remove square brackets
      }
      $(selector).removeClass('jforms-error').prev('.jforms-label').removeClass('jforms-error');
      $(selector).next().filter('span.controlHelper').remove();
      $(selector).parent().next().filter('span.controlHelper').remove();
      $(selector).siblings().filter('span.controlErrorMsg').remove();
  },
  addOk : function(control){
      var selector = '#'+this.form.name+'_'+control.name;
      selector = selector.replace(/\[\]/g, '');//remove square brackets
      if( $(selector).length == 0 ) {
          selector = 'form#'+this.form.name+' span.jforms-ctl-'+control.name; //for radios, date, ...
          selector = selector.replace(/\[\]/g, '');//remove square brackets
      }
      if( $(selector).next().filter('span.controlHelper').length != 0 ) {
          $(selector).next().filter('span.controlHelper').removeClass('controlError').addClass('controlOk');
      } else if( $(selector).parent().next().filter('span.controlHelper').length != 0 ) {//dirty hack if controls are in a div or something (e.g. usefull for checkboxes)
          $(selector).parent().next().filter('span.controlHelper').removeClass('controlError').addClass('controlOk');
      } else {
          $(selector).after('<span class="controlHelper">*</span>');
          $(selector).next().filter('span.controlHelper').removeClass('controlError').addClass('controlOk');
      }
      $(selector).siblings().filter('span.controlErrorMsg').remove();
      $(selector).removeClass('jforms-error').prev('.jforms-label').removeClass('jforms-error');
  },
  addErrorMessage : function(control,message) {
      $(this.errorList).append('<li>'+message+'</li>');
      $('#'+this.form.name+'_'+control.name).addClass('jforms-error').prev('.jforms-label').addClass('jforms-error');
      $('.jforms-ctl-'+control.name +' label').addClass('jforms-error').parent().prev('.jforms-label').addClass('jforms-error');
  },
  end : function(){
    if ($('ul.jforms-error-list li').length == 0 )
        $(this.errorList).fadeOut();
    else {
        $.scrollTo('#'+this.form.name, 500, {offset:-50});
        $(this.errorList).fadeIn();
        this.form.submitted = false;
    }
  }
}




function setErrorOn(ctrlname,message) {
  var frm = jFormsJQ._forms['jform1'];
  var ctrls = frm.controls;
  var ctrl = null;
  for(var i=0; i < ctrls.length; i++){
      if (ctrls[i].name == ctrlname) {
          ctrl = ctrls[i];
          break;
      }
  }
  if (ctrl) {
    if (!message || (message == 1))
        frm.errorDecorator.addError(ctrl,1);
    else if (message == 2)
        frm.errorDecorator.addError(ctrl,2);
    else
        frm.errorDecorator.addErrorMessage(ctrl,message);
  }
}


function requiredDecorator() {
   $('label.jforms-required').each( function() {
           var controlRequired = $('#'+$(this).attr("for"));
           controlRequired.after('<span class="controlHelper controlRequired">*</span>');
    });
   $('span.jforms-label.jforms-required').each( function() {
           $(this).parent().children().last().after('<span class="controlHelper controlRequired">*</span>');
    });
};

jQuery( function() {
    requiredDecorator();
});



var arrayTimeout = [];

function setFieldsetHelperTo( item, state ) {
    var target = item.parents('fieldset').find('.fieldsetHelper');

    if( target.length == 0 )
        return;

    var targetId = -1;

    $('fieldset .fieldsetHelper').each( function(i) {
           if( target[0] == this )
                { targetId = i; }
                } );

    if( targetId == -1 )
        return;

    /* clear timeout so that helper won't disappear if we switch from one field to another in the same fieldset */
    clearTimeout( arrayTimeout[targetId] );

    if( state )
    {
        arrayTimeout[targetId] = setTimeout(function() { target.fadeIn(); }, 200);
    }
    else
    {
        arrayTimeout[targetId] = setTimeout(function() { target.fadeOut(); }, 200);
    }
}











function setAsRequired( formIdArg, fieldName, required ) {
    var reqSelector = '#'+formIdArg+'_'+fieldName;
    reqSelector = reqSelector.replace(/\[\]/g, '');//remove square brackets
    if( $(reqSelector).length == 0 ) {
        reqSelector = 'form#'+formIdArg+' span.jforms-ctl-'+fieldName; //for radios, date, ...
        reqSelector = reqSelector.replace(/\[\]/g, '');//remove square brackets
        fieldName += '[]';
    }
    $(reqSelector).each( function() {
            var associatedLabel = $(this).prev('.jforms-label');
            if( associatedLabel.length == 0 ) {
                associatedLabel = $(this).prev().prev('.jforms-label')
                if( associatedLabel.length == 0 ) {
                        associatedLabel = $(this).parent().prev('.jforms-label');
                }
            }
            if( required ) {
                associatedLabel.addClass('jforms-required');
                if( $(this).hasClass( 'jforms-chkbox' ) ) {//checkboxes
                    if( $(this).parent().next().filter('span.controlHelper').length == 0 ) {
                        $(this).parent().after('<span class="controlHelper controlRequired">*</span>');
                    }
                } else {
                    if( $(this).next().filter('span.controlHelper').length == 0 ) {
                        $(this).after('<span class="controlHelper controlRequired">*</span>');
                    }
                }
                jFormsJQ._forms[formIdArg].getControl(fieldName).required = true;
                jFormsJQ._forms[formIdArg].getControl(fieldName).errRequired = "Le champ '" + jFormsJQ._forms[formIdArg].getControl(fieldName).label + "' est obligatoire";
            } else {
                associatedLabel.removeClass('jforms-error');
                if( $(this).hasClass( 'jforms-chkbox' ) ) {
                    $(this).parent().next().filter('span.controlHelper').remove();
                } else {
                    $(this).next().filter('span.controlHelper').remove();
                }
                jFormsJQ._forms[formIdArg].getControl(fieldName).required = false;
                jFormsJQ._forms[formIdArg].getControl(fieldName).errRequired = "";
            }
            });
}


/* show or hide help according to the event on the field */
function fieldEvent(formIdArg, fieldId) {
    if ($('#'+fieldId).attr('name')) {
        fieldName = $('#'+fieldId).attr('name');
        ctlName = $('#'+fieldId).attr('name').replace(/\[.+\]/g, '');//remove square brackets
        if ($('input.focusClass[name='+fieldName+']').length > 0 || $('select.focusClass[name='+fieldName+']').length > 0 || $('textarea.focusClass[name='+fieldName+']').length > 0  || $('#'+fieldId).siblings('.focusClass').length > 0) {
            jFormsJQ.showHelp(formIdArg, ctlName);
        } else {
            jFormsJQ.hideHelp(formIdArg, ctlName);
            // check form control with JS
            jFormsJQ.verifyControl( jFormsJQ._forms[formIdArg].getControl(ctlName), jFormsJQ._forms[formIdArg] );
        }
    }
}

function manageFieldsFocus( formIdArg ) {
    $('form#'+formIdArg+' .userFormItem input, form#'+formIdArg+' .userFormItem select, form#'+formIdArg+' .userFormItem textarea').live('blur', function() {
            $(this).removeClass('focusClass');
            setTimeout("fieldEvent('"+formIdArg+"', '"+$(this).attr('id')+"')", 100);
        });
    $('form#'+formIdArg+' .userFormItem input, form#'+formIdArg+' .userFormItem select, form#'+formIdArg+' .userFormItem textarea').live('focus', function() {
            $(this).addClass('focusClass');
            fieldEvent(formIdArg, $(this).attr('id'));
        });
    $('form#'+formIdArg+' .userFormItem input, form#'+formIdArg+' .userFormItem select, form#'+formIdArg+' .userFormItem textarea').live('change', function() {
            fieldEvent(formIdArg, $(this).attr('id'));
        });
}


