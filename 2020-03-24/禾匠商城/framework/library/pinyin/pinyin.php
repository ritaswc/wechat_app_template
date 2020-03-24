<?php
/**
 * @name Pinyin_Pinyin
 * @desc 汉字转拼音 (utf8)
 * @author 张顺(zhangshun@baidu.com)
 */

include 'ChinesePinyinTable.php';
class Pinyin_Pinyin {
    /**
     * @desc split string
     * @param string $string
     * @return array
     **/
    private function splitString($string) {
        $arrResult = array();

        $intLen = mb_strlen($string);
        while ($intLen) {
            $arrResult[] = mb_substr($string, 0, 1, 'utf8');
            $string = mb_substr($string, 1, $intLen, 'utf8');
            $intLen = mb_strlen($string);
        }

        return $arrResult;
    }

    /**
     * @desc change to single character list to pinyin list
     * @param array $arrStringList
     * @return array
     **/
    private function toPinyinList($arrStringList) {
        $arrResult = array();

        if (!is_array($arrStringList)) {
            return $arrResult;
        }

        foreach ($arrStringList as $string) {
            switch (strlen($string)) {
                case 1:
                    $arrResult[] = array($string);
                    break;
                case 3:
                    if (isset(Pinyin_ChinesePinyinTable::$arrChinesePinyinTable[$string])) {
                        $arrResult[] = 
                            Pinyin_ChinesePinyinTable::$arrChinesePinyinTable[$string];
                    } else {
                        $arrResult[] = array($string);
                    }
                    break;
                default :
                    $arrResult[] = array($string);
            }
        }

        return $arrResult;
    }

    /**
     * @desc convert chinese(include letter & number) to pinyin
     * @param string $string
     * @param boolean $isSimple
     * @param boolean $isInitial
     * @param boolean $isPolyphone
     * @param boolean $isAll
     * @return mixed
     **/
    public function ChineseToPinyin($string, $isSimple = true, $isInitial = false, 
        $isPolyphone = false, $isAll = false) {

        $result = '';

        if (empty($string)) {
            return $result;
        }

        $arrStringList = self::splitString($string);

        if (!is_array($arrStringList)) {
            return $result;
        }

        $arrPinyinList = self::toPinyinList($arrStringList);

        if (!is_array($arrPinyinList)) {
            return $result;
        }

        if ($isSimple === true) {
            foreach ($arrPinyinList as $arrPinyin) {
                if (empty($arrPinyin)) {
                    continue;
                }
                $result .= $arrPinyin[0];
            }

            return $result;
        }

        $arrFirstPinyin = array_shift($arrPinyinList);

        if (($isInitial !== true) || ($isAll === true)) {
            $arrPrevPinyin = $arrFirstPinyin;
            foreach ($arrPinyinList as $arrPinyin) {
                $arrFullPinyin = array();
                foreach ($arrPrevPinyin as $strPrevPinyin) {
                    foreach ($arrPinyin as $strPinyin) {
                        $arrFullPinyin[] = $strPrevPinyin . $strPinyin;
                    }
                }
                $arrPrevPinyin = $arrFullPinyin;
            }
        }

        if (($isInitial === true) || ($isAll === true)) {
            if (ord($arrFirstPinyin[0]) > 129) {
                $arrPrevInitialPinyin[0] = $arrFirstPinyin[0];
            } else {
                $arrPrevInitialPinyin[0] = substr($arrFirstPinyin[0], 0, 1);
            }
            foreach ($arrPinyinList as $arrPinyin) {
                $arrInitialPinyin = array();
                foreach ($arrPrevInitialPinyin as $strPrevPinyin) {
                    foreach ($arrPinyin as $strPinyin) {
                        if (ord($strPinyin) > 129) {
                            $arrInitialPinyin[] = $strPrevPinyin . $strPinyin;
                        } else {
                            $arrInitialPinyin[] = $strPrevPinyin . substr($strPinyin, 0, 1);
                        }
                    }
                }
                $arrPrevInitialPinyin = $arrInitialPinyin;
            }
        }

        if ($isAll === true) {
            $result['full'] = $arrFullPinyin;
            $result['initial'] = $arrInitialPinyin;
        } elseif ($isPolyphone === true) {
            if (($isInitial === true)) {
                $result = $arrInitialPinyin;
            } else {
                $result = $arrFullPinyin;
            }
        } else {
            if (($isInitial === true)) {
                $result = reset($arrInitialPinyin);
            } else {
                $result = reset($arrFullPinyin);
            }
        }

        return $result;
    }
    /*
        *获取字符串首个字符/首个汉字拼音大写
    */
    public function get_first_char($string){
        $first = '';
        $pinyin = self::ChineseToPinyin($string);
        if(!empty($pinyin)){
            $first = strtoupper(substr($pinyin, 0, 1));
        }
		if (!empty($first) && (ord($first) < 65 || ord($first) > 90)) {
		    return '';
		}
        return $first;
    }
}