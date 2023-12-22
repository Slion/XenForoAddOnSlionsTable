# XenForoAddOnSlionsTable
XenForo 2 Table Add-On

Extends functionality of the built-in table BB code.

# Features

- Add support for `colspan` and `rowspan`
- Add WYSIWYG table editor cell button to merge or split table cells

# Usage

```
[TABLE]
[TR]
[TH][CENTER]One[/CENTER][/TH]
[TH colspan='2'][CENTER]Two & Three[/CENTER][/TH]
[TH][CENTER]Four[/CENTER][/TH]
[/TR]
[TR]
[TD colspan='2' rowspan='2'][CENTER]Up[/CENTER][/TD]
[TD]1[/TD][TD]2[/TD]
[/TR]
[TR]
[TD]3[/TD][TD]4[/TD]
[/TR]
[TR]
[TD]5[/TD][TD]6[/TD]
[TD colspan='2' rowspan='2'][CENTER]Down[/CENTER][/TD]
[/TR]
[TR]
[TD]7[/TD][TD]8[/TD]
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