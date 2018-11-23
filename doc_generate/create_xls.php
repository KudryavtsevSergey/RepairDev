<?

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


try {

    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

    global $USER_FIELD_MANAGER;

    function attemptSendErrors(array $errors)
    {
        if (!empty($errors)) {
            throw new \Exception(implode('<br>', $errors));
        }
    }

    $sectionHeaderStyle = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
        'font' => [
            'size' => 14,
            'bold' => true
        ]
    ];

    $errors = [];

    $carParsingCardId = $_GET['ORDER_ID'] ?? '';
    $siteId = $_GET['site_id'] ?? SITE_ID;

    switch ($siteId) {
        case 's1':
            define('LANG_POSTFIX', '_RU');
            $messages = [
                'YES' => 'Да',
                'NO' => 'Нет',
                'TITLE' => 'Наименование',
                'Z' => 'З',
                'R' => 'Р',
                'KOMR' => 'КОМ P',
                'ADDITIONAL_PACK' => 'Доп упак',
                'NOTE' => 'Примечание',
            ];
            break;
        case 's2':
            define('LANG_POSTFIX', '_EN');
            $messages = [
                'YES' => 'Yes',
                'NO' => 'No',
                'TITLE' => 'Name',
                'Z' => 'Z',
                'R' => 'R',
                'KOMR' => 'KOM R',
                'ADDITIONAL_PACK' => 'Additional pack',
                'NOTE' => 'Note',
            ];
            break;
        default:
            $messages = [
                'YES' => 'Да',
                'NO' => 'Нет',
                'TITLE' => 'Наименование',
                'Z' => 'З',
                'R' => 'Р',
                'KOMR' => 'КОМ P',
                'ADDITIONAL_PACK' => 'Доп упак',
                'NOTE' => 'Примечание',
            ];
            break;
    }

    if (empty($carParsingCardId)) {
        $errors[] = 'Не указан id карты разбора автомобиля.';
    }


    attemptSendErrors($errors);

    $carParsingCard = \classes\stores\CarParsingCard::getById($carParsingCardId);
    $user = CUser::GetById($carParsingCard['UF_USER_ID'])->Fetch();
    $carParsingCardSpareParts = \classes\stores\CarParsingCardSparePart::get(['filter' => ['UF_CAR_PARSING_CARD' => $carParsingCardId]]);
    $spareParts = \classes\stores\SparePart::get(['filter' => ['ID' => array_column($carParsingCardSpareParts, 'UF_SPARE_PART_ID')]]);

    $iblockSections = array_values(array_unique(array_column($spareParts, 'IBLOCK_SECTION_ID')));

    $dbSection = CIBlockSection::GetList(["SORT" => "ASC"], ['ACTIVE' => 'Y', 'ID' => $iblockSections], false, ['ID', 'IBLOCK_ID', 'NAME']);

    $sections = [];
    while ($arSection = $dbSection->GetNext()) {
        //$sectionFields = GetUserFields('IBLOCK_1_SECTION', $arSection['ID']);

        $sections[] = [
            'ID' => $arSection['ID'],
            'NAME' => $arSection['NAME'],
            //'UF_NAME_EN' =>  $sectionFields['UF_NAME_EN']['VALUE'],
            //'UF_NAME_RU' =>  $sectionFields['UF_NAME_RU']['VALUE'],
        ];
    }

    $partsBySections = [];

    foreach ($carParsingCardSpareParts as $key => $item) {
        $item['NUMBER'] = $spareParts[$item['UF_SPARE_PART_ID']]['PROPERTIES']['NUMBER']['VALUE'];
        $item['NAME_EN'] = $spareParts[$item['UF_SPARE_PART_ID']]['PROPERTIES']['NAME_EN']['VALUE'];
        $item['NAME_RU'] = $spareParts[$item['UF_SPARE_PART_ID']]['PROPERTIES']['NAME_RU']['VALUE'];
        $item['UF_ADDITIONAL_PACKAG'] = $item['UF_ADDITIONAL_PACKAG'] ? $messages['YES'] : $messages['NO'];

        $partsBySections[$spareParts[$item['UF_SPARE_PART_ID']]['IBLOCK_SECTION_ID']][$key] = $item;
    }

    foreach ($sections as &$section) {
        $section['ITEMS'] = $partsBySections[$section['ID']];
    }
    unset($section);

    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    $row = 4;
    foreach ($sections as $section) {

        $worksheet->SetCellValue('A' . $row, $section['NAME']);
        $worksheet->mergeCells("A{$row}:G{$row}");

        $worksheet->getStyle("A{$row}:G{$row}")->applyFromArray($sectionHeaderStyle);

        $row++;
        foreach ($section['ITEMS'] as $item) {
            $worksheet->SetCellValue("A{$row}", $item['NUMBER']);
            $worksheet->SetCellValue("B{$row}", $item['NAME_RU']);
            $worksheet->SetCellValue("C{$row}", $item['NAME_EN']);
            $worksheet->SetCellValue("D{$row}", $item['UF_Z']);
            $worksheet->SetCellValue("E{$row}", $item['UF_P']);
            $worksheet->SetCellValue("F{$row}", $item['UF_ADDITIONAL_PACKAG']);
            $worksheet->SetCellValue("G{$row}", $item['UF_NOTE']);

            $row++;
        }
    }

    $headers = [
        [
            'CODE',
            'YEAR MAKE MODEL',
            'VIN',
            'LOT',
            '',
            'DATE',
            'PERSON'
        ],
        [
            '',
            "{$carParsingCard['UF_YEAR_OF_ISSUE']} {$carParsingCard['UF_MAKE_AND_MODEL']}",
            $carParsingCard['UF_VIN_NUMBER'],
            $carParsingCard['UF_LOT_NUMBER'],
            '',
            date('Y-m-d H:i:s'),
            $user['LOGIN']
        ],
        [
            '',
            'Наименование/description',
            '',
            'заказ/order',
            'раз/dism',
            'упак/wrapp',
            'Примечание/notice',
        ]
    ];

    $worksheet->fromArray($headers, NULL, 'A1');

    $worksheet->getStyle("A1:G3")->getFont()->setBold(true);

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];

    $pCellCoordinate = "A1:G" . ($row - 1);

    $worksheet->getStyle($pCellCoordinate)->applyFromArray($styleArray);

    $worksheet->getColumnDimension('A')->setWidth(7);
    $worksheet->getColumnDimension('B')->setWidth(35);
    $worksheet->getColumnDimension('C')->setWidth(30);
    $worksheet->getColumnDimension('D')->setWidth(14);
    $worksheet->getColumnDimension('E')->setWidth(14);
    $worksheet->getColumnDimension('F')->setWidth(14);
    $worksheet->getColumnDimension('G')->setWidth(25);

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="KARTA RAZBORKI.xlsx"');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;

} catch (\Exception $ex) {

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    CAdminMessage::ShowMessage(array('MESSAGE' => $ex->getMessage(), 'TYPE' => 'ERROR'));

}