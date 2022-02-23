Mr.Template 
=========

[![CI](https://github.com/Scarbous/mr_template/actions/workflows/ci.yml/badge.svg?branch=master)](https://github.com/Scarbous/mr_template/actions/workflows/ci.yml)

## What does it do?

EXT:mr_template helps you to organize your TypoScript and TSConfig in your Template-Extension.

## Why have I created this Extension?

My goal when creating template extensions is to be able to reuse code easily and to configure as little as possible by hand.

With Mr.Template, I can create basic templates and build on them.<br> All TypoScripts and TSConfigs are loaded for the respective section of the page tree. (MultiPage support)

## How to use?

1. Basically download and install the extension.<br>It is recommended to install it with composer. 


2. Create your Template-Extension your with the [folder structure](#Folder structure):


3. Create a ```template.yml``` in your Template-Extension and add the config like:
```yml
label: Sample Template
parent: mr_template/base
typoScript:
    - EXT:fluid_styled_content/Configuration/TypoScript
    - EXT:news/Configuration/TypoScript
extensions: # Includes TypoScript in Extensions/[ExtsnsionName]/Configuration/TypoScript
    - fluid_styled_content
    - news
tsConfig: # Can handle TSConfig Files or classes wich implements Scarbous\MrTemplate\Template\Entity\TsConfigInterface
    - EXT:sample_template/Configuration/TsConfig/Page/TCEMAIN.tsconfig
```

4. Define your "site root" and add one empty TypoScript Template to the "site root".


5. Select Template in Sites-Config under Tab "Mr.Template"

### Folder structure

That the system can do the job your Template Extension needs the following folder structure:

```
sample_template
┣ Configuration
┃ ┣ MrTemplate
┃   ┣ default <- Template name, one Extension can handle multiple templates
┃   ┃ ┗ template.yml
┃   ┃
┃   ┗ TsConfig
┃     ┗ Page
┃       ┗ Mod
┃         ┗ WebLayout
┃           ┗ BackendLayouts <- Each BackendLayout get its one file.
┃             ┗ default.tsconfig
┃
┃   ┗ TypoScript <- Default TypoScript
┃     ┣ setup.txt
┃     ┗ constants.txt
┃
┣ Extensions <- Overrides for other extensions
┃ ┗ news
┃   ┗ Configuration
┃     ┗ TypoScript
┃       ┣ setup.txt
┃       ┗ constants.txt
┃
┗ Resources
  ┗ Private
    ┣ Layouts
    ┃ ┗ Page
    ┃
    ┣ Partials
    ┃ ┗ Page
    ┃
    ┗ Templates
      ┗ Page
```

## Default TypoScript and TSConfig

Here are some simple Configs I use realy often in Template-Extensions.

### TypoScript

#### EXT:mr_template/Configuration/TypoScript/Page

Add FluidPage Template based on BackendLayout-Name.

#### EXT:mr_template/Configuration/TypoScript/BackendLayoutBodyClass

Like the folder name say, add the BackendLayout name as class to the body-tag.

#### EXT:mr_template/Configuration/TypoScript/Config

Some basic configs with configurable constants like:
* caching
* concatenateJs
* concatenateCss
* compressJs
* compressCss

### TSConfig

#### Scarbous\\MrTemplate\\Template\\Entity\\BackendLayouts

Adds all BackendLayout files under ```EXT:[sample_extension\]/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts```
