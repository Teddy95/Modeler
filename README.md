# Modeler

<p align="center">
	<img src="http://root.andre-sieverding.de/briefkasten/GithubRepoLogos/Modeler.png" alt="">
	<p align="center">Modeler is a small php template engine.</p>
</p>

-------------

### Benefits

- Define variables and include templates
- Use language variables in json files
- Build fast and easy templates

-------------

### Installation

Inlude Modeler.php and create the Modeler object:

```php
<?php
	include('Modeler/Modeler.php');
	
	$Modeler = new Modeler\main('../templateDirectory', '../languageDirectory/en-us');
	$Modeler->load_template('../template.html');
	
	$Modeler->assign('title', 'Modeler template engine');
	$Modeler->assign('name', 'John Doe');
	
	$Modeler->display();
?>
```

Template file `template.html`:

```html
<html>
    <head>
        <title>{{ title }}</title>
    </head>
    <body>
        <p>My name is {{ name }}!<p>
    </body>
</html>
```

Output:

```
My Name is John Doe!
```

-------------

### Documentaion

[https://github.com/Teddy95/EasyRouter/wiki](https://github.com/Teddy95/Modeler/wiki)

-------------

### Download

- [Releases on Github](https://github.com/Teddy95/Modeler/releases)
- **[Download latest version from Github](https://github.com/Teddy95/Modeler/archive/v0.2.2.zip)**
- [Download master from Github](https://github.com/Teddy95/Modeler/archive/master.zip)

-------------

### Contributors

- [Teddy95](https://github.com/Teddy95)

-------------

### License

The MIT License (MIT) - [View LICENSE.md](https://github.com/Teddy95/Modeler/blob/master/LICENSE.md)
