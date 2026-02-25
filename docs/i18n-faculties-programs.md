# Faculty and Programme Translation Workflow

Faculty and study programme names are translated via vue-i18n using the `code` field from the database. Keys are `faculties.{code}` and `programs.{code}`.

## Adding New Translations

When you add a faculty or study programme via the admin panel:

1. Add the LV label to `resources/js/locales/lv.json` under `faculties` or `programs`
2. Add the EN label to `resources/js/locales/en.json` under `faculties` or `programs`
3. Use the exact `code` value from the database as the key (e.g. if `code` is `FF`, the key is `faculties.FF`)

If a translation is missing, the system falls back to the raw `title` from the database (usually Latvian).

## Discovering Missing Translations

Run the discovery command to list faculty and programme codes in the database that lack translations:

```bash
php artisan i18n:export-faculties-programs --diff
```

This compares the database codes with the keys in `resources/js/locales/lv.json` and prints any missing entries.

## Exporting Full Data

To export all faculty and programme data (e.g. for manual translation):

```bash
php artisan i18n:export-faculties-programs --output=i18n-faculties-programs.json
```

The JSON is written to `storage/app/i18n-faculties-programs.json`.
