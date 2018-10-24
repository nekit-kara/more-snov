<?php
/**
 * Created by PhpStorm.
 * User: ilyak
 * Date: 23.10.2018
 * Time: 23:01
 */

namespace App\Services;

use App\Helpers\FieldHelper;
use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer as CSV;
use Goodby\CSV\Import\Standard\LexerConfig as CSVConfig;

class EvateksService
{
    public $fields = [
        FieldHelper::PRODUCT_ID, // 12320
        FieldHelper::CATEGORY, // Для женщин/Женские халаты/Махровые халаты
        FieldHelper::PRODUCT_NAME, // Женский махровый халат Grace (EP 8002)
        FieldHelper::MANUFACTURER, // POLENTS
        FieldHelper::COLLECTION, // EP "Grace"
        FieldHelper::COUNTRY, // Россия
        FieldHelper::PRICE, // 4950
        FieldHelper::SIZE, // XL (50-52) , 2XL (52-54)
        FieldHelper::COLOR, // лиловый
        FieldHelper::COMPOSITION, // 80% хлопок, 20% пэ
        FieldHelper::DIMENSIONS, // 40, 30, 6
        FieldHelper::PILLOWCASE_NUMBER, // ""
        FieldHelper::PILLOWCASE_SIZE, // ""
        FieldHelper::DUVET_NUMBER, // ""
        FieldHelper::DUVET, // ""
        FieldHelper::SHEET, // ""
        FieldHelper::WEIGHT, // 1,2
        FieldHelper::PHOTOS, // https://evateks.ru/foto/55828b.jpg,https://evateks.ru/foto/55832b.jpg
        FieldHelper::VIDEO, // ""
        FieldHelper::DESCRIPTION, // "Облегченный махровый халат с и оригинальным жаккардовым рисунком..."
    ];

    public $sourcePath = '/var/www/u0335492/data/www/more-snov.ru/storage/providers/evateks/evateks.csv';

    public function parse()
    {
        $config = new CSVConfig();
        $config->setDelimiter(';')
            ->setToCharset('UTF-8');
        $csv = new CSV($config);
        $interpreter = new Interpreter();

        $fields = $this->fields;

        $interpreter->addObserver(function (array $columns) use ($fields) {
            $productData = array_combine($fields, $columns);

            if ($productData[FieldHelper::CATEGORY] !== 'Категория') {
                $service = new ProductService();
                $service->processProductDataRow($productData);
            }
        });

        $csv->parse($this->sourcePath, $interpreter);
    }
}