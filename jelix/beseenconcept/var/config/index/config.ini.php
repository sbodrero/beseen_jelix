;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

startModule=structure
startAction="default:index"

[responses]
html=myHtmlResponse
[modules]
beseenconcept.access=2

structure.access=2

news.access=2

jauth.access=2
auth.access=2
sitemap.access=2
[coordplugins]
auth="index/auth.coord.ini.php"


