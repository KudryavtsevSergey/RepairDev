<?

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


try {

    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

    function attemptSendErrors(array $errors)
    {
        if (!empty($errors)) {
            throw new \Exception(implode('<br>', $errors));
        }
    }

    $errors = [];

    $carParsingCardId = $_GET['ORDER_ID'] ?? '';
    $siteId = $_GET['site_id'] ?? SITE_ID;

    switch ($siteId){
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
    $carParsingCardSpareParts = \classes\stores\CarParsingCardSparePart::get(['filter' => ['UF_CAR_PARSING_CARD' => $carParsingCardId]]);
    $spareParts = \classes\stores\SparePart::get(['filter' => ['ID' => array_column($carParsingCardSpareParts, 'UF_SPARE_PART_ID')]]);

    $data = array_map(function ($item) use ($spareParts, $messages) {
        $data = [
            'number' => $spareParts[$item['UF_SPARE_PART_ID']]['PROPERTIES']['NUMBER']['VALUE'],
            'name' => $spareParts[$item['UF_SPARE_PART_ID']]['PROPERTIES']['NAME' . LANG_POSTFIX]['VALUE'],
            'z' => $item['UF_Z'],
            'p' => $item['UF_P'],
            'komr' => $item['UF_KOMR'],
            'additional_packag' => $item['UF_ADDITIONAL_PACKAG'] ? $messages['YES'] : $messages['NO'],
            'note' => $item['UF_NOTE'],
        ];
        return $data;
    }, $carParsingCardSpareParts);

    $data = array_merge([['#', $messages['TITLE'], $messages['Z'], $messages['R'], $messages['KOMR'], $messages['ADDITIONAL_PACK'], $messages['NOTE']]], $data);

    //dm(['$carParsingCard' => $carParsingCard]);
    //die();

    $spreadsheet = new Spreadsheet();
    $worksheet = $spreadsheet->getActiveSheet();

    $a1Value = "{$carParsingCard['UF_LOT_NUMBER']} {$carParsingCard['UF_YEAR_OF_ISSUE']} {$carParsingCard['UF_MAKE_AND_MODEL']} {$carParsingCard['UF_COLOUR']} {$carParsingCard['UF_VIN_NUMBER']}";

    $spreadsheet->setActiveSheetIndex(0)
        ->setCellValue('A1', $a1Value);

    $worksheet->fromArray($data, NULL, 'A2');

    $styleArray = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '00000000'],
            ],
        ],
    ];

    $pCellCoordinate = "A2:G" . (count($data) + 1);

    $worksheet->getStyle($pCellCoordinate)->applyFromArray($styleArray);

    $worksheet->getColumnDimension('A')->setAutoSize(true);
    $worksheet->getColumnDimension('B')->setAutoSize(true);
    $worksheet->getColumnDimension('C')->setAutoSize(true);
    $worksheet->getColumnDimension('D')->setAutoSize(true);
    $worksheet->getColumnDimension('E')->setAutoSize(true);
    $worksheet->getColumnDimension('F')->setAutoSize(true);
    $worksheet->getColumnDimension('G')->setAutoSize(true);

    $worksheet->mergeCells('A1:G1');

    $spreadsheet->setActiveSheetIndex(0);

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $a1Value . '.xlsx"');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;

} catch (\Exception $ex) {

    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
    CAdminMessage::ShowMessage(array('MESSAGE' => $ex->getMessage(), 'TYPE' => 'ERROR'));

}