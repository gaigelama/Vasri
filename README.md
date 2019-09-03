[![Build Status](https://scrutinizer-ci.com/g/ExoUNX/Vasri/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ExoUNX/Vasri/build-status/dev) 
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ExoUNX/Vasri/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ExoUNX/Vasri/?branch=dev)
![License](https://img.shields.io/github/license/ExoUNX/Vasri.svg)

# Vasri
Easy subresource integrity and versioning for local assets

## Install

```
composer require exounx/vasri
```

## Usage

For CSS

```
<link rel="stylesheet" {!! vasri('/css/app.css') !!}/>
```

For JS

```
<script {!! vasri('/js/app.js') !!}></script>
```

## License

The MIT License (MIT). Please see [License File](https://github.com/ExoUNX/Vasri/blob/master/LICENSE) for more information.
