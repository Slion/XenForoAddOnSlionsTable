# XenForoAddOnSlionsTable
XenForo 2 Table Add-On

Extends functionality of the built-in table BB code.

# Features

- Add support for `colspan` and `rowspan`
- Add WYSIWYG table editor cell button to merge or split table cells

# Release process

Go to the root of your XenForo installation and set the new release [Version ID] by running:

`php cmd.php xf-addon:bump-version Slions/Table --version-id 2020100`

Generate the release archive using the following command:

`php cmd.php xf-addon:build-release Slions/Table`

The generated ZIP file can be found in the following folder:

`/src/addons/Slions/Table/_releases`


# Resources

- [Froala table editor option]
- [Modifying Froala editor options]
- [Version ID]

[Froala table editor option]: https://froala.com/wysiwyg-editor/docs/options/#table
[Modifying Froala editor options]: https://xenforo.com/community/threads/modifying-froala-editor-options.161305/
[Version ID]: https://xenforo.com/docs/dev/add-on-structure/#recommended-version-id-format