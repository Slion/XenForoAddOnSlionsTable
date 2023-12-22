# XenForoAddOnSlionsTable
XenForo 2 Table Add-On

Extends functionality of the built-in table BB code.

# Download

Download from [XenForo.com].

# Features

- Add `TH` and `TD` BB code support for `colspan`, `rowspan`, and `style` options
- Add `TABLE` BB code support for `style` and `class` options
- Add WYSIWYG table editor cell button to merge or split table cells
- Add WYSIWYG table editor cell background button to change cell color
- Add WYSIWYG table editor resizing handle
- Add WYSIWYG table editor style button: dashed borders and alternate rows

# Usage

```
[TABLE style='width: 50%;margin-left: auto;margin-right: auto;' class='fr-alternate-rows']
[TR]
[TH style='width: 26.8507%;'][CENTER]One[/CENTER][/TH]
[TH colspan='2' style='width: 33.0302%;'][CENTER]Two & Three[/CENTER][/TH]
[TH style='width: 39.9206%;'][CENTER]Four[/CENTER][/TH]
[/TR]
[TR]
[TD colspan='2' rowspan='2' style='width: 41.812%;'][CENTER]Up[/CENTER][/TD]
[TD style='width: 18.0712%;'][CENTER]1[/CENTER][/TD]
[TD style='width: 39.9206%;'][CENTER]2[/CENTER][/TD]
[/TR]
[TR]
[TD style='width: 18.0712%;'][CENTER]3[/CENTER][/TD]
[TD style='width: 39.9206%;'][CENTER]4[/CENTER][/TD]
[/TR]
[TR]
[TD style='width: 26.8507%;'][CENTER]5[/CENTER][/TD]
[TD style='width: 14.9526%;'][CENTER]6[/CENTER][/TD]
[TD colspan='2' rowspan='2' style='background-color: rgb(235, 107, 86);width: 58.0199%;'][CENTER][COLOR=rgb(65, 168, 95)][B]Down[/B][/COLOR][/CENTER][/TD]
[/TR]
[TR]
[TD style='width: 26.8507%;'][CENTER]7[/CENTER][/TD]
[TD style='width: 14.9526%;'][CENTER]8[/CENTER][/TD]
[/TR]
[/TABLE]
```

# Release process

Go to the root of your XenForo installation and set the new release [Version ID] by running:

`php cmd.php xf-addon:bump-version Slions/Table --version-id 2020100`

Generate the release archive using the following command:

`php cmd.php xf-addon:build-release Slions/Table`

The generated ZIP file can be found in the following folder:

`/src/addons/Slions/Table/_releases`


# Resources

- [Foala table plugin]
- [Froala table editor option]
- [Froala table editor cell styles example]
- [Modifying Froala editor options]
- [Version ID]

[Foala table plugin]: https://froala.com/table-plugin/
[Froala table editor option]: https://froala.com/wysiwyg-editor/docs/options/#table
[Froala table editor cell styles example]: https://froala.com/wysiwyg-editor/examples/table-cell-styles/
[Modifying Froala editor options]: https://xenforo.com/community/threads/modifying-froala-editor-options.161305/
[Version ID]: https://xenforo.com/docs/dev/add-on-structure/#recommended-version-id-format
[XenForo.com]: https://xenforo.com/community/resources/slions-table.9300/