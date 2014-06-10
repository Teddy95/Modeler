# Modeler

<p align="center">
	<img src="http://i.imgur.com/TB2mpvf.png" alt="">
</p>

Modeler is a great php template engine.

- Define variables and include templates
- Use language variables in json files
- Build fast and easy templates

### Installation

Inlude Modeler.php and create the Modeler object:

```php
<?php
	include('Modeler/Modeler.php');
	
	$Modeler = new Teddy95\Modeler\template('../templateDirectory', '../languageDirectory/en-us');
	$Modeler->load_template('../index.html');
	
	$Modeler->assign('title', 'Modeler template engine');
	$Modeler->assign('name', 'John Doe');
	
	$Modeler->display();
?>
```

Template file `index.html`:

```html
<html>
    <head>
        <title>{{ title }}</title>
    </head>
    <body>
        <p>My name is {{ name }}<p>
    </body>
</html>
```

-------------

### Documentaion

[https://github.com/Teddy95/EasyRouter/wiki](https://github.com/Teddy95/Modeler/wiki)

-------------

### Download

- [Releases on Github](https://github.com/Teddy95/Modeler/releases)
- **[Download latest version from Github](https://github.com/Teddy95/Modeler/archive/v0.1.0.zip)**
- [Download master from Github](https://github.com/Teddy95/Modeler/archive/master.zip)

-------------

### Contributors

- [Teddy95](https://github.com/Teddy95)

-------------

### License

The MIT License (MIT) - [View LICENSE.md](https://github.com/Teddy95/Modeler/blob/master/LICENSE.md)
