<?php

namespace Econnect\Vcard;

use Stringable;
use Carbon\Carbon;
use DateTimeInterface;
use Astrotomic\ConditionalProxy\HasConditionalCalls;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Econnect\Vcard\Properties\Adr;
use Econnect\Vcard\Properties\Bday;
use Econnect\Vcard\Properties\Email;
use Econnect\Vcard\Properties\Gender;
use Econnect\Vcard\Properties\Kind;
use Econnect\Vcard\Properties\Note;
use Econnect\Vcard\Properties\Org;
use Econnect\Vcard\Properties\Photo;
use Econnect\Vcard\Properties\Role;
use Econnect\Vcard\Properties\Source;
use Econnect\Vcard\Properties\Tel;
use Econnect\Vcard\Properties\Title;
use Econnect\Vcard\Properties\Url;
use Econnect\Vcard\Properties\Prod;
use Econnect\Vcard\Properties\SocialNetwork;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class Vcard implements Responsable, Stringable
{
    use HasConditionalCalls;

    protected ?string $fullName = null;

    protected ?string $namePrefix = null;

    protected ?string $firstName = null;

    protected ?string $middleName = null;

    protected ?string $lastName = null;

    protected ?string $nameSuffix = null;

    protected array $properties = [];

    public static function make(): self
    {
        return new static();
    }

    /**
     * Полное имя
     *
     * @param string|null $fullName
     * @return self
     */
    public function fullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Имя
     *
     * @param string|null $lastName Фамилия
     * @param string|null $firstName Имя
     * @param string|null $middleName Отчество
     * @param string|null $prefix Префикс
     * @param string|null $suffix Суффикс
     * @return self
     */
    public function name(
        ?string $lastName = null,
        ?string $firstName = null,
        ?string $middleName = null,
        ?string $prefix = null,
        ?string $suffix = null
    ): self {
        $this->namePrefix = $prefix;
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->nameSuffix = $suffix;

        return $this;
    }

    /**
     * Почта
     *
     * @param string $email Почта (значение)
     * @param array $types Тип почты (массив констант Econnect\Vcard\Properties\Email)
     * @return self
     */
    public function email(string $email, array $types = [Email::INTERNET]): self
    {
        $this->properties[] = new Email($email, $types);

        return $this;
    }

    /**
     * Телефон
     *
     * @param string $number Номер телефона
     * @param array $types Тип телефона (массив констант Econnect\Vcard\Properties\Tel)
     * @return self
     */
    public function tel(string $number, array $types = [Tel::VOICE]): self
    {
        $this->properties[] = new Tel($number, $types);

        return $this;
    }

    /**
     * Веб-сайт
     *
     * @param string $url ссылка
     * @return self
     */
    public function url(string $url): self
    {
        $this->properties[] = new Url($url);

        return $this;
    }

    /**
     * Фотография пользователя
     *
     * @param string $photo Строковый эквивалент изображения
     * @return self
     */
    public function photo(string $photo): self
    {
        $this->properties[] = new Photo($photo);

        return $this;
    }

    /**
     * Дата рождения
     *
     * @param DateTimeInterface $bday
     * @return self
     */
    public function bday(DateTimeInterface $bday): self
    {
        $this->properties[] = new Bday($bday);

        return $this;
    }

    /**
     * Категория контакта
     *
     * @param string $kind Группа (константа Econnect\Vcard\Properties\Kind)
     * @return self
     */
    public function kind(string $kind): self
    {
        $this->properties[] = new Kind($kind);

        return $this;
    }

    /**
     * Гендер
     *
     * @param string $gender (константа Econnect\Vcard\Properties\Gender)
     * @return self
     */
    public function gender(string $gender): self
    {
        $this->properties[] = new Gender($gender);

        return $this;
    }

    /**
     * Организация
     *
     * @param string|null $company наименование
     * @param string|null $unit отдел (департамент)
     * @param string|null $team наименование команды
     * @return self
     */
    public function org(?string $company = null, ?string $unit = null, ?string $team = null): self
    {
        $this->properties[] = new Org($company, $unit, $team);

        return $this;
    }

    /**
     * Официальное название должности или звания человека 
     *
     * @param string $title
     * @return self
     */
    public function title(string $title): self
    {
        $this->properties[] = new Title($title);

        return $this;
    }

    /**
     * Роль или функция человека в организации
     *
     * @param string $role
     * @return self
     */
    public function role(string $role): self
    {
        $this->properties[] = new Role($role);

        return $this;
    }

    /**
     * Адрес
     *
     * @param string|null $poBox Почтовый ящик
     * @param string|null $extendedAddress Дополнительный адрес (например, квартира, блок)
     * @param string|null $streetAddress Улица и номер дома
     * @param string|null $locality Город или населённый пункт
     * @param string|null $region Регион, область или штат
     * @param string|null $postalCode Почтовый индекс
     * @param string|null $countryName Название страны
     * @param array $types Типы адреса (константа Econnect\Vcard\Properties\Adr)
     * @return self
     */
    public function adr(
        ?string $poBox = null,
        ?string $extendedAddress = null,
        ?string $streetAddress = null,
        ?string $locality = null,
        ?string $region = null,
        ?string $postalCode = null,
        ?string $countryName = null,
        array $types = [Adr::WORK]
    ): self {
        $this->properties[] = new Adr(
            $poBox,
            $extendedAddress,
            $streetAddress,
            $locality,
            $region,
            $postalCode,
            $countryName,
            $types
        );

        return $this;
    }

    /**
     * Заметка
     *
     * @param string $note
     * @return self
     */
    public function note(string $note): self
    {
        $this->properties[] = new Note($note);

        return $this;
    }

    /**
     * Источник
     *
     * @param string $source
     * @return self
     */
    public function source(string $source): self
    {
        $this->properties[] = new Source($source);

        return $this;
    }

    /**
     * Социальная сеть (для iOS)
     *
     * @param string $type Текст на кнопке
     * @param string $url Ссылка
     * @return self
     */
    public function socialNetwork(string $type, string $url): self
    {
        $this->properties[] = new SocialNetwork($type, $url);

        return $this;
    }

    /**
     * Продукт, выпустивший vCard
     *
     * @param string $prod
     * @return self
     */
    public function prod(string $prod): self
    {
        $this->properties[] = new Prod($prod);

        return $this;
    }

    /**
     * Сформированный vCard
     *
     * @return string
     */
    public function __toString(): string
    {
        return collect([
            'BEGIN:VCARD',
            'VERSION:3.0',
            "FN;CHARSET=UTF-8:{$this->getFullName()}",
            $this->hasNameParts() ? "N;CHARSET=UTF-8:{$this->lastName};{$this->firstName};{$this->middleName};{$this->namePrefix};{$this->nameSuffix}" : null,
            array_map('strval', $this->properties),
            sprintf('REV:%s', Carbon::now()->toISOString()),
            'END:VCARD',
        ])->flatten()->filter()->implode(PHP_EOL);
    }

    /**
     * Отправка контакта через протокол HTTP
     *
     * @param Request $request
     * @return void
     */
    public function toResponse($request)
    {
        $content = strval($this);

        $filename = Str::of($this->getFullName())->slug('_')->append('.vcf');

        return new Response($content, 200, [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Content-Type' => 'text/vcard; charset=utf-8',
            'Content-Length' => strlen($content),
            'Content-Disposition' => HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $filename,
                $filename->ascii()->replace('%', '')
            ),
        ]);
    }

    /**
     * Получить полное имя
     *
     * @return string
     */
    protected function getFullName(): string
    {
        return $this->fullName ?? collect([
            $this->namePrefix,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->nameSuffix,
        ])->filter()->implode(' ');
    }

    /**
     * Получить частичное имя (по заполненным полям)
     *
     * @return boolean
     */
    protected function hasNameParts(): bool
    {
        return !empty(array_filter([
            $this->namePrefix,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->nameSuffix,
        ]));
    }
}
