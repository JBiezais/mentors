<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Atribūtam :attribute ir jābūt pieņemtam.',
    'accepted_if' => 'Atribūtam :attribute ir jābūt pieņemtam, kad :other ir :value.',
    'active_url' => ':attribute nav derīgs URL.',
    'after' => ':attribute ir jābūt datumam pēc :date.',
    'after_or_equal' => ':attribute ir jābūt datumam, kas ir pēc vai vienāds ar :date.',
    'alpha' => ':attribute drīkst saturēt tikai burtus.',
    'alpha_dash' => ':attribute drīkst saturēt tikai burtus, ciparus, domuzīmes un pasvītrojumus.',
    'alpha_num' => ':attribute drīkst saturēt tikai burtus un ciparus.',
    'array' => ':attribute ir jābūt masīvam.',
    'ascii' => ':attribute drīkst saturēt tikai vienbaita alfanumeriskos simbolus un zīmes.',
    'before' => ':attribute ir jābūt datumam pirms :date.',
    'before_or_equal' => ':attribute ir jābūt datumam, kas ir pirms vai vienāds ar :date.',
    'between' => [
        'array' => ':attribute jāsatur no :min līdz :max vienībām.',
        'file' => ':attribute jābūt starp :min un :max kilobaitiem.',
        'numeric' => ':attribute jābūt starp :min un :max.',
        'string' => ':attribute jābūt starp :min un :max rakstzīmēm.',
    ],
    'boolean' => ':attribute laukumam jābūt patiesam vai nepatiesam.',
    'confirmed' => ':attribute apstiprinājums neatbilst.',
    'current_password' => 'Parole ir nepareiza.',
    'date' => ':attribute nav derīgs datums.',
    'date_equals' => ':attribute ir jābūt datumam, kas ir vienāds ar :date.',
    'date_format' => ':attribute neatbilst formātam :format.',
    'decimal' => ':attribute ir jābūt ar :decimal decimālvietām.',
    'declined' => ':attribute ir jābūt noraidītam.',
    'declined_if' => ':attribute ir jābūt noraidītam, kad :other ir :value.',
    'different' => ':attribute un :other ir jābūt atšķirīgiem.',
    'digits' => ':attribute ir jābūt :digits cipariem.',
    'digits_between' => ':attribute jābūt starp :min un :max cipariem.',
    'dimensions' => ':attribute ir nederīgi attēla izmēri.',
    'distinct' => ':attribute laukumā ir dublikāts.',
    'doesnt_end_with' => ':attribute nedrīkst beigties ar vienu no šiem: :values.',
    'doesnt_start_with' => ':attribute nedrīkst sākties ar vienu no šiem: :values.',
    'email' => ':attribute ir jābūt derīgai e-pasta adresei.',
    'ends_with' => ':attribute ir jābeidzas ar vienu no šiem: :values.',
    'enum' => 'Izvēlētais :attribute ir nederīgs.',
    'exists' => 'Izvēlētais :attribute ir nederīgs.',
    'file' => ':attribute ir jābūt failam.',
    'filled' => ':attribute laukumam ir jābūt vērtībai.',
    'gt' => [
        'array' => ':attribute jāsatur vairāk nekā :value vienības.',
        'file' => ':attribute jābūt lielākam par :value kilobaitiem.',
        'numeric' => ':attribute jābūt lielākam par :value.',
        'string' => ':attribute jābūt lielākam par :value rakstzīmēm.',
    ],
    'gte' => [
        'array' => ':attribute jāsatur :value vienības vai vairāk.',
        'file' => ':attribute jābūt lielākam vai vienādam ar :value kilobaitiem.',
        'numeric' => ':attribute jābūt lielākam vai vienādam ar :value.',
        'string' => ':attribute jābūt lielākam vai vienādam ar :value rakstzīmēm.',
    ],
    'image' => ':attribute ir jābūt attēlam.',
    'in' => 'Izvēlētais :attribute ir nederīgs.',
    'in_array' => ':attribute lauks neeksistē :other.',
    'integer' => ':attribute ir jābūt veselam skaitlim.',
    'ip' => ':attribute ir jābūt derīgai IP adresei.',
    'ipv4' => ':attribute ir jābūt derīgai IPv4 adresei.',
    'ipv6' => ':attribute ir jābūt derīgai IPv6 adresei.',
    'json' => ':attribute ir jābūt derīgai JSON virknei.',
    'lowercase' => ':attribute ir jābūt ar mazajiem burtiem.',
    'lt' => [
        'array' => ':attribute jāsatur mazāk nekā :value vienības.',
        'file' => ':attribute jābūt mazākam par :value kilobaitiem.',
        'numeric' => ':attribute jābūt mazākam par :value.',
        'string' => ':attribute jābūt mazākam par :value rakstzīmēm.',
    ],
    'lte' => [
        'array' => ':attribute nedrīkst saturēt vairāk nekā :value vienības.',
        'file' => ':attribute jābūt mazākam vai vienādam ar :value kilobaitiem.',
        'numeric' => ':attribute jābūt mazākam vai vienādam ar :value.',
        'string' => ':attribute jābūt mazākam vai vienādam ar :value rakstzīmēm.',
    ],
    'mac_address' => ':attribute ir jābūt derīgai MAC adresei.',
    'max' => [
        'array' => ':attribute nedrīkst saturēt vairāk nekā :max vienības.',
        'file' => ':attribute nedrīkst būt lielāks par :max kilobaitiem.',
        'numeric' => ':attribute nedrīkst būt lielāks par :max.',
        'string' => ':attribute nedrīkst būt lielāks par :max rakstzīmēm.',
    ],
    'max_digits' => ':attribute nedrīkst būt vairāk nekā :max cipari.',
    'mimes' => ':attribute ir jābūt failam no šāda tipa: :values.',
    'mimetypes' => ':attribute ir jābūt failam no šāda tipa: :values.',
    'min' => [
        'array' => ':attribute jāsatur vismaz :min vienības.',
        'file' => ':attribute jābūt vismaz :min kilobaitiem.',
        'numeric' => ':attribute jābūt vismaz :min.',
        'string' => ':attribute jābūt vismaz :min rakstzīmēm.',
    ],
    'min_digits' => ':attribute jābūt vismaz :min cipariem.',
    'multiple_of' => ':attribute ir jābūt :value kārtotājs.',
    'not_in' => 'Izvēlētais :attribute ir nederīgs.',
    'not_regex' => ':attribute formāts ir nederīgs.',
    'numeric' => ':attribute ir jābūt skaitlim.',
    'password' => [
        'letters' => 'Laukam :attribute jāsatur vismaz viens burts.',
        'mixed' => 'Laukam :attribute jāsatur vismaz viens lielais un viens mazais burts.',
        'numbers' => 'Laukam :attribute jāsatur vismaz viens skaitlis.',
        'symbols' => 'Laukam :attribute jāsatur vismaz viens simbols.',
        'uncompromised' => 'Norādītais :attribute ir bijis datu noplūdē. Lūdzu, izvēlieties citu :attribute.',
    ],
    'present' => ':attribute laukam jābūt aizpildītam.',
    'prohibited' => ':attribute lauks ir aizliegts.',
    'prohibited_if' => ':attribute lauks ir aizliegts, ja :other ir :value.',
    'prohibited_unless' => ':attribute lauks ir aizliegts, ja vien :other nav :values.',
    'prohibits' => ':attribute lauks aizliedz :other klātbūtni.',
    'regex' => ':attribute formāts ir nederīgs.',
    'required' => 'Lauks :attribute ir obligāts.',
    'required_array_keys' => ':attribute laukā jābūt šādām ierakstiem: :values.',
    'required_if' => ':attribute lauks ir nepieciešams, ja :other ir :value.',
    'required_if_accepted' => ':attribute lauks ir nepieciešams, ja :other ir pieņemts.',
    'required_unless' => ':attribute lauks ir nepieciešams, ja vien :other nav :values.',
    'required_with' => ':attribute lauks ir nepieciešams, ja :values ir klāt.',
    'required_with_all' => ':attribute lauks ir nepieciešams, ja visi :values ir klāt.',
    'required_without' => ':attribute lauks ir nepieciešams, ja :values nav klāt.',
    'required_without_all' => ':attribute lauks ir nepieciešams, ja neviens no :values nav klāt.',
    'same' => ':attribute un :other jāsakrīt.',
    'size' => [
        'array' => ':attribute jāsatur :size vienības.',
        'file' => ':attribute jābūt :size kilobaitiem.',
        'numeric' => ':attribute jābūt :size.',
        'string' => ':attribute jābūt :size rakstzīmēm.',
    ],
    'starts_with' => ':attribute jāsākas ar vienu no šiem: :values.',
    'string' => ':attribute jābūt virknei.',
    'timezone' => ':attribute jābūt derīgai laika zonai.',
    'unique' => ':attribute jau ir aizņemts.',
    'uploaded' => ':attribute neizdevās augšupielādēt.',
    'uppercase' => ':attribute jābūt lielajiem burtiem.',
    'url' => ':attribute jābūt derīgai URL.',
    'ulid' => ':attribute jābūt derīgai ULID.',
    'uuid' => ':attribute jābūt derīgai UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
