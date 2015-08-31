<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="hu" lang="hu">
<head>
<title>{$mail_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset={$mail_charset}" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset={$mail_charset}" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<style type="text/css"><!--
{literal}
body {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	margin: 0;
	padding: 0;
	text-align: left;
}
p, h1, h2, h3, h4, h5, h6 {
	padding:0;
	margin-top: 6px;
	margin-bottom: 6px;
}
table, td, th {
	font-size: 1em;
}
h1, h2, h3 {
	font-family: "Trebuchet MS", Trebuchet, Arial, Helvetica, sans-serif;
}
h2 {
	font-size: 1.8em;
}
h3 {
	font-size: 1.5em;
}
.centered {
	text-align:center;
}
a {
	color: #000;
	font-weight:bold;
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}

p#date {
	font-weight: bold;
}

#unsubscribe {
	padding-top: 20px;
}
{/literal}
-->
</style>
</head>
<body>
	<h1 id="subject">{$mail_sender|htmlspecialchars}</h1>
	<h2 id="subject">{$mail_title|htmlspecialchars}</h2>
	<p id="date">{$mail_date}</p>
	<div>{$mail_email}</div>
	<div id="message">
	{textformat style="email"}
		{$mail_message}
	{/textformat}
	</div>
</body>
</html>
