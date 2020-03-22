<p align="center">
  <a href="https://kompophp.com" target="_blank">
    <img src="https://kompophp.com/img/vuravel-logo-big.png" width="200" height="133" alt="Kompo-logo" />
  </a>
</p>
<h6 align="center">
    <img alt="GitHub last commit" src="https://img.shields.io/github/last-commit/vuravel/components.svg">
    <img src="https://img.shields.io/npm/dt/vuravel-components.svg?style=flat-square" alt="Downloads" />
    <img src="https://img.shields.io/npm/v/vuravel-components.svg?style=flat-square" alt="Version" />
    <img src="https://img.shields.io/npm/l/vuravel-components.svg?style=flat-square" alt="License" />
</h6>
<h3 align="center">
    <a href="https://kompophp.com" target="_blank">kompophp.com</a>
    &nbsp;&nbsp;&bull;&nbsp;&nbsp;
    <a href="https://kompophp.com/docs" target="_blank">Documentation</a>
    &nbsp;&nbsp;&bull;&nbsp;&nbsp;
    <a href="https://kompophp.com/examples" target="_blank">Demos</a>
    &nbsp;&nbsp;&bull;&nbsp;&nbsp;
    <a href="https://twitter.com/vuravel" target="_blank">Twitter</a>
</h3>

# vuravel/components

`kompo\kompo` is a library of components to help you write forms in a matter of seconds. No matter how complex the user input you need, you can find a component that handles that. You can also pick from different styles to suit your needs.

> Refer to the the website for the most up-to-date information, demos and the complete detailed documentation on how to use this package: <a href="https://kompophp.com">kompophp.com</a>


## REQUIREMENTS

You need to have a `Laravel 5.6+` application installed on your local server.  
You need `composer` to pull the vendor packages.  
`Vue.js 2.0+` is already shipped with a Laravel installation, so nothing to do here.  
`Node.js` & `npm` to build and pull the Front-End modules.  

## INSTALLATION

#### Composer - Back-End setup

If you have a Laravel 5.6+ application installed, you may install `vuravel/form` via `composer` by running the following terminal command at your project's root folder:

```sh
composer require vuravel/components
```

#### Npm - Front-End setup

To pull the front-end module into your development environment, you will need to have `nodejs` and `npm` installed on your machine. Then you may run this command:

```sh
npm install --save vuravel-components
```

Once the install process is finished, you should import the javascript modules in your `app.js` . This will import all the Vue components from `vuravel/form` into your project and you will be able to use them everywhere in your Vue.js code.

```js
//app.js
window.Vue = require('vue');

//Adding vuravel after Vue has been required
require('vuravel-components')
```

And to import the scss code, add this line to your `app.scss` :

```js
//app.scss

//For Forms: Pick your favorite form style
@import  'vuravel-form/sass/bootstrap-style';
//@import  'vuravel-form/sass/md-filled-style';
//@import  'vuravel-form/sass/md-outlined-style';
//@import  'vuravel-form/sass/floating-style';

//For Catalogs:
@import  'vuravel-catalog/sass/catalog';

//For Menus: Choose between minimal and classic style
@import  'vuravel-menu/sass/minimal-style';
//@import  'vuravel-menu/sass/classic-style';
```

After that just compile the assets.

```sh
npm run dev
```

And reference them in your template.

```html
<!-- header -->
<link href="{{ mix('css/app.css') }}" rel="stylesheet">

<!-- scripts -->
<script src="{{ mix('js/app.js') }}"></script>
```

You are now ready to start creating Vuravel components!

## DOCUMENTATION

Please refer to the website's complete <a href="https://kompophp.com/docs" target="_blank">Documentation</a>

## COMPONENTS API

<a href="https://kompophp.com/api" target="_blank">API</a>

## DEMOS

<a href="https://kompophp.com/examples" target="_blank">Examples</a>