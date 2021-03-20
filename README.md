**** Notes for using user_upload.php

The database user: should have admin privileges in order to create a database table
Table to be created is called ‘userupload’

The program expects the input file to have csv extension. It doesn’t check for bad filename.

For example use:

Note, where -u and -p use your own username, password (for user for admin rights)
To run create_table:
--file users.csv --create_table -h localhost -u userupload -p userupload
or --create_table

To insert:  where --file (use your own csv file, has .csv extension)
--file users.csv -h localhost -u userupload -p userupload

To --dry_run:
--file users.csv --dry_run -h localhost -u userupload -p userupload

To --help:
--help --file users.csv -create_table -h localhost -u userupload -p userupload
or
--help --

If –create-table is issued, the system exits after the table is created.

After the table is created, the user can issue the command to insert.
The program will create a table called ‘userupload’.

Validations:
Emails: no apostrophes in email allowed.
