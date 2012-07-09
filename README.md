A collection of tools and utilities for Code Igniter 2.0+


dip
=============

This is a working DIP for Code Igniter 2.0+, allowing you to use code igniter's libraries, views and models inside external applications (for exemple, to integrate code igniter inside a forum or blog application).

Contrary to other DIP for CI this one does not load anything beside the strict core, thus no request dispatching is made, several ci default libs are not automatically loaded (benchmark, ...) and no events are sent to hooks. In fact, hooks are not even loaded.

For exemple, with this your code in "my_cool_forum.php" could do something like

```php
...
$ci = get_instance();
$ci->load->model('user');
$ci->user->update_postcount(42);
...
```

Without any overhead or mapping to a CI controller/action.

If you have a custom base controller (usually application/core/MY_Controller.php) it will be used instead of the standard CI_Controller


custom_db_drivers
=============

Code Igniter 2 does not allow to overload its database drivers. This folder includes three things:

1. a custom loader which lets you overload code igniter's database drivers

2. a custom database driver for postgresql which allows you to use the superior pg_query_params rather than pg_query in your queries, and a custom profiler overload to take this change into account

3. a custom database driver for mysql which gives you access to "replace into", "insert ignore" and "insert ... on duplicate key"
