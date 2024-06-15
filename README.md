# Laravel vCard ver. 3.0

Удобный класс для создания файлов vCard. В этой версии используется стандарт 3-ей версии для совместимости со многими устройствами

## Установка

Вы можете установить пакет через composer:

```bash
composer require econnect/laravel-vcard
```

## Использование

```php
use Econnect\Vcard\Properties\Email;
use Econnect\Vcard\Properties\Gender;
use Econnect\Vcard\Properties\Kind;
use Econnect\Vcard\Properties\Tel;
use Econnect\Vcard\Vcard;
use Carbon\Carbon;

Vcard::make()
    ->kind(Kind::INDIVIDUAL)
    ->gender(Gender::MALE)
    ->fullName('Иван Петрович Сидоров')
    ->name('Сидоров', 'Иван', 'Петрович')
    ->email('ivan.sidorov@mail.ru')
    ->email('ivan.sidorov@company.ru', [Email::WORK, Email::INTERNET])
    ->tel('+79123456789', [Tel::HOME, Tel::VOICE])
    ->tel('+79098765432', [Tel::WORK, Tel::VOICE])
    ->tel('+79012345678', [Tel::CELL, Tel::VOICE])
    ->url('https://ivansidorov.ru')
    ->url('https://company.ru')
    ->bday(Carbon::parse('1985-07-15'))
    ->adr('', '', 'ул. Ленина, д. 1', 'Москва', '', '101000', 'Россия')
    ->photo('data:image/jpeg;base64,'.base64_encode(file_get_contents(__DIR__.'/stubs/photo.jpg')))
    ->title('Заместитель директора по развитию')
    ->role('Исполнительный директор')
    ->org('Компания', 'Команда Почты', 'Отдел спам-фильтрации')
    ->note('Привет, мир')
;
```

```vcard
BEGIN:VCARD
VERSION:3.0
FN;CHARSET=UTF-8:Иван Петрович Сидоров
N;CHARSET=UTF-8:Сидоров;Иван;Петрович;;
KIND:individual
GENDER:M
EMAIL;TYPE=INTERNET:ivan.sidorov@mail.ru
EMAIL;TYPE=WORK;TYPE=INTERNET:ivan.sidorov@company.ru
TEL;TYPE=HOME;TYPE=VOICE:+79123456789
TEL;TYPE=WORK;TYPE=VOICE:+79098765432
TEL;TYPE=CELL;TYPE=VOICE:+79012345678
URL:https://ivansidorov.ru
URL:https://company.ru
BDAY:1985-07-15
ADR;TYPE=WORK:;;ул. Ленина, д. 1;Москва;;101000;Россия
PHOTO;data:image/jpeg;base64,...
TITLE:Заместитель директора по развитию
ROLE:Исполнительный директор
ORG:Компания;Команда Почты;Отдел спам-фильтрации
REV:2021-02-25T10:30:45.000000Z
END:VCARD
```
