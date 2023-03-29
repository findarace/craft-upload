# Upload plugin for Craft CMS

The unassuming front end asset uploader for Craft CMS.
Use as a standalone uploader or as field in one of your forms:

## Features:

*   Drop to upload
*   Reorder & remove uploads
*   Asset previews
*   Customisable
*   It's Vanilla (Zero dependencies writtin in battle tested javascript)

### Requirements

This plugin requires Craft CMS 4.0.0 or later.

### Installation

To install the plugin, follow these steps:

1.  Install with Composer via:

        composer require findarace/upload

2.  In the Control Panel, go to Settings → Plugins and click the “Install” button for Uploadit.

## Using Uploadit

**Options**

    {{ craft.upload.uploader({
    	id: 'myUid',
    	name: 'myFieldName',
        assets: [],

        field: 'images',
        element: entry,

        volume: 'myvolume',
    	folder: 'my/folder/path',

        preview: 'image',
        transform: 'square',

        limit: 5,
        allowReorder: true,
        allowRemove: true,
        customClass: 'custom--class',

    }) }}

#### `id` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

#### `name` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

#### `assets` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

#### `field` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

#### `element` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

#### `volume` option

To drag elements from one list into another, both lists must have the same `group` value.
You can also define whether lists can give away, give and keep a copy (`clone`), and receive elements.

*   name: `String` — group name
*   pull: `true|false|'clone'|function` — ability to move from the list. `clone` — copy the item, rather than move.
*   put: `true|false|["foo", "bar"]|function` — whether elements can be added from other lists, or an array of group names from which elements can be taken.
*   revertClone: `boolean` — revert cloned element to initial position after moving to a another list.

---

**Example Standalone Usage**

    {{ craft.upload.uploader({
    	id: 'myUid',
    	name: 'myFieldName',
    	assets: [],
    	volume: 'myvolume',
    	folder: 'my/folder/path',
    	preview: 'image',
    	transform: 'square',
        themeColour: '#ff00ff',
    }) }}

<p align="left"><img width="450px" src="resources/img/customise-labels.png" alt="Linkit"></a></p>

**Example Form Usage**

    {{ craft.upload.uploader({
    	id: 'myUid',
    	name: 'myFieldName',
        assets: [],
        field: 'images',
        element: 345678,
        preview: 'image',
        transform: 'square',
        themeColour: '#ff00ff',
    }) }}

<p align="left"><img width="450px" src="resources/img/customise-labels.png" alt="Linkit"></a></p>

## Roadmap

*   [ ] Better validation
*   [ ] Translate javascript strings

Note: This plugin will become a paid add-on when the Craft Plugin store becomes available.

Brought to you by [findarace.com](https://findarace.com)
