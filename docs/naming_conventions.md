# Naming Conventions

### Cases

-   Database fields: snake_case
-   PHP variables and functions: camelCase
-   JS variables and functions: camelCase
-   PHP files: kebab-case
-   JS files: camelCase
-   controllers 'endpoints': snake_case
-   controllers parameters: snake_case
-   HTML ids: camelCase
-   HTML fields names: snake_case
-   CSS classes: kebab-case

### Database

-   Tables: UPPER_SNAKE_CASE
-   Columns: column names must be prefixed with 3 upperscore characteres that identify their table and an underscore (ex: MEDICINES.id -> MHD_id). Prefer clarity over brevity.

### Correspondence between endpoints and database operations

-   get -> SELECT
-   add/create -> ADD
-   update/modify -> UPDATE
-   delete -> DELETE
