<?php
namespace Tersoal\DynaMapBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CollationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->getCollationChoices(),
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'collation_choice';
    }

    /**
     * Get all available collations for DB fields.
     *
     * @return array
     */
    private function getCollationChoices()
    {
        $collationChoices = [];

        foreach ($this->getCollations() as $collation) {
            $charsetName = null;

            foreach ($this->getCharacterSets() as $characterSet) {
                if ($characterSet['CHARACTER_SET_NAME'] === $collation['CHARACTER_SET_NAME']) {
                    $charsetName = $characterSet['DESCRIPTION'];
                    continue;
                }
            }

            if (!$charsetName) {
                continue;
            }

            $collationChoices[ $charsetName ][ $collation['COLLATION_NAME'] ] = $collation['COLLATION_NAME'];
        }

        ksort($collationChoices);

        return $collationChoices;
    }

    /**
     * Character sets extracted from Mysql Schema with sql:
     *
     *          SELECT * FROM information_schema.COLLATIONS
     *
     * @return array
     */
    private function getCharacterSets()
    {
        $characterSets = array(
            array('CHARACTER_SET_NAME' => 'big5','DEFAULT_COLLATE_NAME' => 'big5_chinese_ci','DESCRIPTION' => 'Big5 Traditional Chinese','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'dec8','DEFAULT_COLLATE_NAME' => 'dec8_swedish_ci','DESCRIPTION' => 'DEC West European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'cp850','DEFAULT_COLLATE_NAME' => 'cp850_general_ci','DESCRIPTION' => 'DOS West European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'hp8','DEFAULT_COLLATE_NAME' => 'hp8_english_ci','DESCRIPTION' => 'HP West European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'koi8r','DEFAULT_COLLATE_NAME' => 'koi8r_general_ci','DESCRIPTION' => 'KOI8-R Relcom Russian','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'latin1','DEFAULT_COLLATE_NAME' => 'latin1_swedish_ci','DESCRIPTION' => 'cp1252 West European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'latin2','DEFAULT_COLLATE_NAME' => 'latin2_general_ci','DESCRIPTION' => 'ISO 8859-2 Central European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'swe7','DEFAULT_COLLATE_NAME' => 'swe7_swedish_ci','DESCRIPTION' => '7bit Swedish','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'ascii','DEFAULT_COLLATE_NAME' => 'ascii_general_ci','DESCRIPTION' => 'US ASCII','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'ujis','DEFAULT_COLLATE_NAME' => 'ujis_japanese_ci','DESCRIPTION' => 'EUC-JP Japanese','MAXLEN' => '3'),
            array('CHARACTER_SET_NAME' => 'sjis','DEFAULT_COLLATE_NAME' => 'sjis_japanese_ci','DESCRIPTION' => 'Shift-JIS Japanese','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'hebrew','DEFAULT_COLLATE_NAME' => 'hebrew_general_ci','DESCRIPTION' => 'ISO 8859-8 Hebrew','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'tis620','DEFAULT_COLLATE_NAME' => 'tis620_thai_ci','DESCRIPTION' => 'TIS620 Thai','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'euckr','DEFAULT_COLLATE_NAME' => 'euckr_korean_ci','DESCRIPTION' => 'EUC-KR Korean','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'koi8u','DEFAULT_COLLATE_NAME' => 'koi8u_general_ci','DESCRIPTION' => 'KOI8-U Ukrainian','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'gb2312','DEFAULT_COLLATE_NAME' => 'gb2312_chinese_ci','DESCRIPTION' => 'GB2312 Simplified Chinese','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'greek','DEFAULT_COLLATE_NAME' => 'greek_general_ci','DESCRIPTION' => 'ISO 8859-7 Greek','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'cp1250','DEFAULT_COLLATE_NAME' => 'cp1250_general_ci','DESCRIPTION' => 'Windows Central European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'gbk','DEFAULT_COLLATE_NAME' => 'gbk_chinese_ci','DESCRIPTION' => 'GBK Simplified Chinese','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'latin5','DEFAULT_COLLATE_NAME' => 'latin5_turkish_ci','DESCRIPTION' => 'ISO 8859-9 Turkish','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'armscii8','DEFAULT_COLLATE_NAME' => 'armscii8_general_ci','DESCRIPTION' => 'ARMSCII-8 Armenian','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'utf8','DEFAULT_COLLATE_NAME' => 'utf8_general_ci','DESCRIPTION' => 'UTF-8 Unicode','MAXLEN' => '3'),
            array('CHARACTER_SET_NAME' => 'ucs2','DEFAULT_COLLATE_NAME' => 'ucs2_general_ci','DESCRIPTION' => 'UCS-2 Unicode','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'cp866','DEFAULT_COLLATE_NAME' => 'cp866_general_ci','DESCRIPTION' => 'DOS Russian','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'keybcs2','DEFAULT_COLLATE_NAME' => 'keybcs2_general_ci','DESCRIPTION' => 'DOS Kamenicky Czech-Slovak','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'macce','DEFAULT_COLLATE_NAME' => 'macce_general_ci','DESCRIPTION' => 'Mac Central European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'macroman','DEFAULT_COLLATE_NAME' => 'macroman_general_ci','DESCRIPTION' => 'Mac West European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'cp852','DEFAULT_COLLATE_NAME' => 'cp852_general_ci','DESCRIPTION' => 'DOS Central European','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'latin7','DEFAULT_COLLATE_NAME' => 'latin7_general_ci','DESCRIPTION' => 'ISO 8859-13 Baltic','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'utf8mb4','DEFAULT_COLLATE_NAME' => 'utf8mb4_general_ci','DESCRIPTION' => 'UTF-8 Unicode','MAXLEN' => '4'),
            array('CHARACTER_SET_NAME' => 'cp1251','DEFAULT_COLLATE_NAME' => 'cp1251_general_ci','DESCRIPTION' => 'Windows Cyrillic','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'utf16','DEFAULT_COLLATE_NAME' => 'utf16_general_ci','DESCRIPTION' => 'UTF-16 Unicode','MAXLEN' => '4'),
            array('CHARACTER_SET_NAME' => 'utf16le','DEFAULT_COLLATE_NAME' => 'utf16le_general_ci','DESCRIPTION' => 'UTF-16LE Unicode','MAXLEN' => '4'),
            array('CHARACTER_SET_NAME' => 'cp1256','DEFAULT_COLLATE_NAME' => 'cp1256_general_ci','DESCRIPTION' => 'Windows Arabic','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'cp1257','DEFAULT_COLLATE_NAME' => 'cp1257_general_ci','DESCRIPTION' => 'Windows Baltic','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'utf32','DEFAULT_COLLATE_NAME' => 'utf32_general_ci','DESCRIPTION' => 'UTF-32 Unicode','MAXLEN' => '4'),
            array('CHARACTER_SET_NAME' => 'binary','DEFAULT_COLLATE_NAME' => 'binary','DESCRIPTION' => 'Binary pseudo charset','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'geostd8','DEFAULT_COLLATE_NAME' => 'geostd8_general_ci','DESCRIPTION' => 'GEOSTD8 Georgian','MAXLEN' => '1'),
            array('CHARACTER_SET_NAME' => 'cp932','DEFAULT_COLLATE_NAME' => 'cp932_japanese_ci','DESCRIPTION' => 'SJIS for Windows Japanese','MAXLEN' => '2'),
            array('CHARACTER_SET_NAME' => 'eucjpms','DEFAULT_COLLATE_NAME' => 'eucjpms_japanese_ci','DESCRIPTION' => 'UJIS for Windows Japanese','MAXLEN' => '3')
        );

        return $characterSets;
    }

    /**
     * Character sets extracted from Mysql Schema with sql:
     *
     *          SELECT * FROM information_schema.CHARACTER_SETS
     *
     * @return array
     */
    private function getCollations()
    {
        $collations = array(
            array('COLLATION_NAME' => 'big5_chinese_ci','CHARACTER_SET_NAME' => 'big5','ID' => '1','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'big5_bin','CHARACTER_SET_NAME' => 'big5','ID' => '84','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'dec8_swedish_ci','CHARACTER_SET_NAME' => 'dec8','ID' => '3','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'dec8_bin','CHARACTER_SET_NAME' => 'dec8','ID' => '69','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp850_general_ci','CHARACTER_SET_NAME' => 'cp850','ID' => '4','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp850_bin','CHARACTER_SET_NAME' => 'cp850','ID' => '80','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'hp8_english_ci','CHARACTER_SET_NAME' => 'hp8','ID' => '6','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'hp8_bin','CHARACTER_SET_NAME' => 'hp8','ID' => '72','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'koi8r_general_ci','CHARACTER_SET_NAME' => 'koi8r','ID' => '7','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'koi8r_bin','CHARACTER_SET_NAME' => 'koi8r','ID' => '74','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_german1_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '5','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_swedish_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '8','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_danish_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '15','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_german2_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '31','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '2'),
            array('COLLATION_NAME' => 'latin1_bin','CHARACTER_SET_NAME' => 'latin1','ID' => '47','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_general_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '48','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_general_cs','CHARACTER_SET_NAME' => 'latin1','ID' => '49','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin1_spanish_ci','CHARACTER_SET_NAME' => 'latin1','ID' => '94','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin2_czech_cs','CHARACTER_SET_NAME' => 'latin2','ID' => '2','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '4'),
            array('COLLATION_NAME' => 'latin2_general_ci','CHARACTER_SET_NAME' => 'latin2','ID' => '9','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin2_hungarian_ci','CHARACTER_SET_NAME' => 'latin2','ID' => '21','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin2_croatian_ci','CHARACTER_SET_NAME' => 'latin2','ID' => '27','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin2_bin','CHARACTER_SET_NAME' => 'latin2','ID' => '77','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'swe7_swedish_ci','CHARACTER_SET_NAME' => 'swe7','ID' => '10','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'swe7_bin','CHARACTER_SET_NAME' => 'swe7','ID' => '82','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ascii_general_ci','CHARACTER_SET_NAME' => 'ascii','ID' => '11','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ascii_bin','CHARACTER_SET_NAME' => 'ascii','ID' => '65','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ujis_japanese_ci','CHARACTER_SET_NAME' => 'ujis','ID' => '12','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ujis_bin','CHARACTER_SET_NAME' => 'ujis','ID' => '91','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'sjis_japanese_ci','CHARACTER_SET_NAME' => 'sjis','ID' => '13','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'sjis_bin','CHARACTER_SET_NAME' => 'sjis','ID' => '88','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'hebrew_general_ci','CHARACTER_SET_NAME' => 'hebrew','ID' => '16','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'hebrew_bin','CHARACTER_SET_NAME' => 'hebrew','ID' => '71','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'tis620_thai_ci','CHARACTER_SET_NAME' => 'tis620','ID' => '18','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '4'),
            array('COLLATION_NAME' => 'tis620_bin','CHARACTER_SET_NAME' => 'tis620','ID' => '89','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'euckr_korean_ci','CHARACTER_SET_NAME' => 'euckr','ID' => '19','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'euckr_bin','CHARACTER_SET_NAME' => 'euckr','ID' => '85','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'koi8u_general_ci','CHARACTER_SET_NAME' => 'koi8u','ID' => '22','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'koi8u_bin','CHARACTER_SET_NAME' => 'koi8u','ID' => '75','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'gb2312_chinese_ci','CHARACTER_SET_NAME' => 'gb2312','ID' => '24','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'gb2312_bin','CHARACTER_SET_NAME' => 'gb2312','ID' => '86','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'greek_general_ci','CHARACTER_SET_NAME' => 'greek','ID' => '25','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'greek_bin','CHARACTER_SET_NAME' => 'greek','ID' => '70','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1250_general_ci','CHARACTER_SET_NAME' => 'cp1250','ID' => '26','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1250_czech_cs','CHARACTER_SET_NAME' => 'cp1250','ID' => '34','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '2'),
            array('COLLATION_NAME' => 'cp1250_croatian_ci','CHARACTER_SET_NAME' => 'cp1250','ID' => '44','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1250_bin','CHARACTER_SET_NAME' => 'cp1250','ID' => '66','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1250_polish_ci','CHARACTER_SET_NAME' => 'cp1250','ID' => '99','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'gbk_chinese_ci','CHARACTER_SET_NAME' => 'gbk','ID' => '28','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'gbk_bin','CHARACTER_SET_NAME' => 'gbk','ID' => '87','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin5_turkish_ci','CHARACTER_SET_NAME' => 'latin5','ID' => '30','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin5_bin','CHARACTER_SET_NAME' => 'latin5','ID' => '78','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'armscii8_general_ci','CHARACTER_SET_NAME' => 'armscii8','ID' => '32','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'armscii8_bin','CHARACTER_SET_NAME' => 'armscii8','ID' => '64','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8_general_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '33','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8_bin','CHARACTER_SET_NAME' => 'utf8','ID' => '83','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8_unicode_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '192','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_icelandic_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '193','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_latvian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '194','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_romanian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '195','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_slovenian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '196','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_polish_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '197','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_estonian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '198','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_spanish_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '199','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_swedish_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '200','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_turkish_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '201','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_czech_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '202','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_danish_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '203','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_lithuanian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '204','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_slovak_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '205','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_spanish2_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '206','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_roman_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '207','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_persian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '208','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_esperanto_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '209','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_hungarian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '210','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_sinhala_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '211','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_german2_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '212','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_croatian_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '213','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_unicode_520_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '214','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_vietnamese_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '215','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8_general_mysql500_ci','CHARACTER_SET_NAME' => 'utf8','ID' => '223','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ucs2_general_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '35','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ucs2_bin','CHARACTER_SET_NAME' => 'ucs2','ID' => '90','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'ucs2_unicode_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '128','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_icelandic_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '129','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_latvian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '130','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_romanian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '131','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_slovenian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '132','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_polish_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '133','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_estonian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '134','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_spanish_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '135','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_swedish_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '136','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_turkish_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '137','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_czech_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '138','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_danish_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '139','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_lithuanian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '140','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_slovak_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '141','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_spanish2_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '142','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_roman_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '143','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_persian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '144','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_esperanto_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '145','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_hungarian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '146','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_sinhala_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '147','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_german2_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '148','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_croatian_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '149','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_unicode_520_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '150','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_vietnamese_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '151','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'ucs2_general_mysql500_ci','CHARACTER_SET_NAME' => 'ucs2','ID' => '159','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp866_general_ci','CHARACTER_SET_NAME' => 'cp866','ID' => '36','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp866_bin','CHARACTER_SET_NAME' => 'cp866','ID' => '68','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'keybcs2_general_ci','CHARACTER_SET_NAME' => 'keybcs2','ID' => '37','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'keybcs2_bin','CHARACTER_SET_NAME' => 'keybcs2','ID' => '73','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'macce_general_ci','CHARACTER_SET_NAME' => 'macce','ID' => '38','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'macce_bin','CHARACTER_SET_NAME' => 'macce','ID' => '43','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'macroman_general_ci','CHARACTER_SET_NAME' => 'macroman','ID' => '39','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'macroman_bin','CHARACTER_SET_NAME' => 'macroman','ID' => '53','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp852_general_ci','CHARACTER_SET_NAME' => 'cp852','ID' => '40','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp852_bin','CHARACTER_SET_NAME' => 'cp852','ID' => '81','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin7_estonian_cs','CHARACTER_SET_NAME' => 'latin7','ID' => '20','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin7_general_ci','CHARACTER_SET_NAME' => 'latin7','ID' => '41','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin7_general_cs','CHARACTER_SET_NAME' => 'latin7','ID' => '42','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'latin7_bin','CHARACTER_SET_NAME' => 'latin7','ID' => '79','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8mb4_general_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '45','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8mb4_bin','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '46','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf8mb4_unicode_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '224','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_icelandic_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '225','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_latvian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '226','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_romanian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '227','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_slovenian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '228','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_polish_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '229','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_estonian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '230','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_spanish_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '231','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_swedish_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '232','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_turkish_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '233','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_czech_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '234','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_danish_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '235','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_lithuanian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '236','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_slovak_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '237','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_spanish2_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '238','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_roman_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '239','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_persian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '240','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_esperanto_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '241','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_hungarian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '242','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_sinhala_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '243','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_german2_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '244','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_croatian_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '245','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_unicode_520_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '246','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf8mb4_vietnamese_ci','CHARACTER_SET_NAME' => 'utf8mb4','ID' => '247','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'cp1251_bulgarian_ci','CHARACTER_SET_NAME' => 'cp1251','ID' => '14','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1251_ukrainian_ci','CHARACTER_SET_NAME' => 'cp1251','ID' => '23','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1251_bin','CHARACTER_SET_NAME' => 'cp1251','ID' => '50','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1251_general_ci','CHARACTER_SET_NAME' => 'cp1251','ID' => '51','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1251_general_cs','CHARACTER_SET_NAME' => 'cp1251','ID' => '52','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf16_general_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '54','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf16_bin','CHARACTER_SET_NAME' => 'utf16','ID' => '55','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf16_unicode_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '101','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_icelandic_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '102','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_latvian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '103','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_romanian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '104','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_slovenian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '105','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_polish_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '106','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_estonian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '107','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_spanish_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '108','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_swedish_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '109','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_turkish_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '110','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_czech_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '111','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_danish_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '112','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_lithuanian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '113','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_slovak_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '114','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_spanish2_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '115','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_roman_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '116','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_persian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '117','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_esperanto_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '118','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_hungarian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '119','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_sinhala_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '120','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_german2_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '121','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_croatian_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '122','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_unicode_520_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '123','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16_vietnamese_ci','CHARACTER_SET_NAME' => 'utf16','ID' => '124','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf16le_general_ci','CHARACTER_SET_NAME' => 'utf16le','ID' => '56','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf16le_bin','CHARACTER_SET_NAME' => 'utf16le','ID' => '62','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1256_general_ci','CHARACTER_SET_NAME' => 'cp1256','ID' => '57','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1256_bin','CHARACTER_SET_NAME' => 'cp1256','ID' => '67','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1257_lithuanian_ci','CHARACTER_SET_NAME' => 'cp1257','ID' => '29','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1257_bin','CHARACTER_SET_NAME' => 'cp1257','ID' => '58','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp1257_general_ci','CHARACTER_SET_NAME' => 'cp1257','ID' => '59','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf32_general_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '60','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf32_bin','CHARACTER_SET_NAME' => 'utf32','ID' => '61','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'utf32_unicode_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '160','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_icelandic_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '161','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_latvian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '162','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_romanian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '163','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_slovenian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '164','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_polish_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '165','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_estonian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '166','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_spanish_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '167','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_swedish_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '168','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_turkish_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '169','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_czech_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '170','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_danish_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '171','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_lithuanian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '172','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_slovak_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '173','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_spanish2_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '174','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_roman_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '175','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_persian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '176','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_esperanto_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '177','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_hungarian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '178','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_sinhala_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '179','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_german2_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '180','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_croatian_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '181','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_unicode_520_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '182','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'utf32_vietnamese_ci','CHARACTER_SET_NAME' => 'utf32','ID' => '183','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '8'),
            array('COLLATION_NAME' => 'binary','CHARACTER_SET_NAME' => 'binary','ID' => '63','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'geostd8_general_ci','CHARACTER_SET_NAME' => 'geostd8','ID' => '92','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'geostd8_bin','CHARACTER_SET_NAME' => 'geostd8','ID' => '93','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp932_japanese_ci','CHARACTER_SET_NAME' => 'cp932','ID' => '95','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'cp932_bin','CHARACTER_SET_NAME' => 'cp932','ID' => '96','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'eucjpms_japanese_ci','CHARACTER_SET_NAME' => 'eucjpms','ID' => '97','IS_DEFAULT' => 'Yes','IS_COMPILED' => 'Yes','SORTLEN' => '1'),
            array('COLLATION_NAME' => 'eucjpms_bin','CHARACTER_SET_NAME' => 'eucjpms','ID' => '98','IS_DEFAULT' => '','IS_COMPILED' => 'Yes','SORTLEN' => '1')
        );

        return $this->array_sort($collations, 'COLLATION_NAME', SORT_ASC);
    }

    private function array_sort($array, $on, $order = SORT_ASC)
    {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort($sortable_array);
                    break;
                case SORT_DESC:
                    arsort($sortable_array);
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }
}