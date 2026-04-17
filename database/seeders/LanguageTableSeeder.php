<?php
namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('languages')->truncate();    
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');   

        $language = array(
            array(
                "sort_code" => "en",
                "name" => "English",
                "nativeName" => "English"
            ),
            array(
                "sort_code" => "ab",
                "name" => "Abkhaz",
                "nativeName" => "аҧсуа"
            ),
            array(
                "sort_code" => "aa",
                "name" => "Afar",
                "nativeName" => "Afaraf"
            ),
            array(
                "sort_code" => "af",
                "name" => "Afrikaans",
                "nativeName" => "Afrikaans"
            ),
            array(
                "sort_code" => "ak",
                "name" => "Akan",
                "nativeName" => "Akan"
            ),
            array(
                "sort_code" => "sq",
                "name" => "Albanian",
                "nativeName" => "Shqip"
            ),
            array(
                "sort_code" => "am",
                "name" => "Amharic",
                "nativeName" => "አማርኛ"
            ),
            array(
                "sort_code" => "ar",
                "name" => "Arabic",
                "nativeName" => "العربية"
            ),
            array(
                "sort_code" => "an",
                "name" => "Aragonese",
                "nativeName" => "Aragonés"
            ),
            array(
                "sort_code" => "hy",
                "name" => "Armenian",
                "nativeName" => "Հայերեն"
            ),
            array(
                "sort_code" => "as",
                "name" => "Assamese",
                "nativeName" => "অসমীয়া"
            ),
            array(
                "sort_code" => "av",
                "name" => "Avaric",
                "nativeName" => "авар мацӀ, магӀарул мацӀ"
            ),
            array(
                "sort_code" => "ae",
                "name" => "Avestan",
                "nativeName" => "avesta"
            ),
            array(
                "sort_code" => "ay",
                "name" => "Aymara",
                "nativeName" => "aymar aru"
            ),
            array(
                "sort_code" => "az",
                "name" => "Azerbaijani",
                "nativeName" => "azərbaycan dili"
            ),
            array(
                "sort_code" => "bm",
                "name" => "Bambara",
                "nativeName" => "bamanankan"
            ),
            array(
                "sort_code" => "ba",
                "name" => "Bashkir",
                "nativeName" => "башҡорт теле"
            ),
            array(
                "sort_code" => "eu",
                "name" => "Basque",
                "nativeName" => "euskara, euskera"
            ),
            array(
                "sort_code" => "be",
                "name" => "Belarusian",
                "nativeName" => "Беларуская"
            ),
            array(
                "sort_code" => "bn",
                "name" => "Bengali",
                "nativeName" => "বাংলা"
            ),
            array(
                "sort_code" => "bh",
                "name" => "Bihari",
                "nativeName" => "भोजपुरी"
            ),
            array(
                "sort_code" => "bi",
                "name" => "Bislama",
                "nativeName" => "Bislama"
            ),
            array(
                "sort_code" => "bs",
                "name" => "Bosnian",
                "nativeName" => "bosanski jezik"
            ),
            array(
                "sort_code" => "br",
                "name" => "Breton",
                "nativeName" => "brezhoneg"
            ),
            array(
                "sort_code" => "bg",
                "name" => "Bulgarian",
                "nativeName" => "български език"
            ),
            array(
                "sort_code" => "my",
                "name" => "Burmese",
                "nativeName" => "ဗမာစာ"
            ),
            array(
                "sort_code" => "ca",
                "name" => "Catalan; Valencian",
                "nativeName" => "Català"
            ),
            array(
                "sort_code" => "ch",
                "name" => "Chamorro",
                "nativeName" => "Chamoru"
            ),
            array(
                "sort_code" => "ce",
                "name" => "Chechen",
                "nativeName" => "нохчийн мотт"
            ),
            array(
                "sort_code" => "ny",
                "name" => "Chichewa; Chewa; Nyanja",
                "nativeName" => "chiCheŵa, chinyanja"
            ),
            array(
                "sort_code" => "zh",
                "name" => "Chinese",
                "nativeName" => "中文 (Zhōngwén), 汉语, 漢語"
            ),
            array(
                "sort_code" => "cv",
                "name" => "Chuvash",
                "nativeName" => "чӑваш чӗлхи"
            ),
            array(
                "sort_code" => "kw",
                "name" => "Cornish",
                "nativeName" => "Kernewek"
            ),
            array(
                "sort_code" => "co",
                "name" => "Corsican",
                "nativeName" => "corsu, lingua corsa"
            ),
            array(
                "sort_code" => "cr",
                "name" => "Cree",
                "nativeName" => "ᓀᐦᐃᔭᐍᐏᐣ"
            ),
            array(
                "sort_code" => "hr",
                "name" => "Croatian",
                "nativeName" => "hrvatski"
            ),
            array(
                "sort_code" => "cs",
                "name" => "Czech",
                "nativeName" => "česky, čeština"
            ),
            array(
                "sort_code" => "da",
                "name" => "Danish",
                "nativeName" => "dansk"
            ),
            array(
                "sort_code" => "dv",
                "name" => "Divehi; Dhivehi; Maldivian;",
                "nativeName" => "ދިވެހި"
            ),
            array(
                "sort_code" => "nl",
                "name" => "Dutch",
                "nativeName" => "Nederlands, Vlaams"
            ),
            array(
                "sort_code" => "eo",
                "name" => "Esperanto",
                "nativeName" => "Esperanto"
            ),
            array(
                "sort_code" => "et",
                "name" => "Estonian",
                "nativeName" => "eesti, eesti keel"
            ),
            array(
                "sort_code" => "ee",
                "name" => "Ewe",
                "nativeName" => "Eʋegbe"
            ),
            array(
                "sort_code" => "fo",
                "name" => "Faroese",
                "nativeName" => "føroyskt"
            ),
            array(
                "sort_code" => "fj",
                "name" => "Fijian",
                "nativeName" => "vosa Vakaviti"
            ),
            array(
                "sort_code" => "fi",
                "name" => "Finnish",
                "nativeName" => "suomi, suomen kieli"
            ),
            array(
                "sort_code" => "fr",
                "name" => "French",
                "nativeName" => "français, langue française"
            ),
            array(
                "sort_code" => "ff",
                "name" => "Fula; Fulah; Pulaar; Pular",
                "nativeName" => "Fulfulde, Pulaar, Pular"
            ),
            array(
                "sort_code" => "gl",
                "name" => "Galician",
                "nativeName" => "Galego"
            ),
            array(
                "sort_code" => "ka",
                "name" => "Georgian",
                "nativeName" => "ქართული"
            ),
            array(
                "sort_code" => "de",
                "name" => "German",
                "nativeName" => "Deutsch"
            ),
            array(
                "sort_code" => "el",
                "name" => "Greek, Modern",
                "nativeName" => "Ελληνικά"
            ),
            array(
                "sort_code" => "gn",
                "name" => "Guaraní",
                "nativeName" => "Avañeẽ"
            ),
            array(
                "sort_code" => "gu",
                "name" => "Gujarati",
                "nativeName" => "ગુજરાતી"
            ),
            array(
                "sort_code" => "ht",
                "name" => "Haitian; Haitian Creole",
                "nativeName" => "Kreyòl ayisyen"
            ),
            array(
                "sort_code" => "ha",
                "name" => "Hausa",
                "nativeName" => "Hausa, هَوُسَ"
            ),
            array(
                "sort_code" => "he",
                "name" => "Hebrew (modern)",
                "nativeName" => "עברית"
            ),
            array(
                "sort_code" => "hz",
                "name" => "Herero",
                "nativeName" => "Otjiherero"
            ),
            array(
                "sort_code" => "hi",
                "name" => "Hindi",
                "nativeName" => "हिन्दी, हिंदी"
            ),
            array(
                "sort_code" => "ho",
                "name" => "Hiri Motu",
                "nativeName" => "Hiri Motu"
            ),
            array(
                "sort_code" => "hu",
                "name" => "Hungarian",
                "nativeName" => "Magyar"
            ),
            array(
                "sort_code" => "ia",
                "name" => "Interlingua",
                "nativeName" => "Interlingua"
            ),
            array(
                "sort_code" => "id",
                "name" => "Indonesian",
                "nativeName" => "Bahasa Indonesia"
            ),
            array(
                "sort_code" => "ie",
                "name" => "Interlingue",
                "nativeName" => "Originally called Occidental; then Interlingue after WWII"
            ),
            array(
                "sort_code" => "ga",
                "name" => "Irish",
                "nativeName" => "Gaeilge"
            ),
            array(
                "sort_code" => "ig",
                "name" => "Igbo",
                "nativeName" => "Asụsụ Igbo"
            ),
            array(
                "sort_code" => "ik",
                "name" => "Inupiaq",
                "nativeName" => "Iñupiaq, Iñupiatun"
            ),
            array(
                "sort_code" => "io",
                "name" => "Ido",
                "nativeName" => "Ido"
            ),
            array(
                "sort_code" => "is",
                "name" => "Icelandic",
                "nativeName" => "Íslenska"
            ),
            array(
                "sort_code" => "it",
                "name" => "Italian",
                "nativeName" => "Italiano"
            ),
            array(
                "sort_code" => "iu",
                "name" => "Inuktitut",
                "nativeName" => "ᐃᓄᒃᑎᑐᑦ"
            ),
            array(
                "sort_code" => "ja",
                "name" => "Japanese",
                "nativeName" => "日本語 (にほんご／にっぽんご)"
            ),
            array(
                "sort_code" => "jv",
                "name" => "Javanese",
                "nativeName" => "basa Jawa"
            ),
            array(
                "sort_code" => "kl",
                "name" => "Kalaallisut, Greenlandic",
                "nativeName" => "kalaallisut, kalaallit oqaasii"
            ),
            array(
                "sort_code" => "kn",
                "name" => "Kannada",
                "nativeName" => "ಕನ್ನಡ"
            ),
            array(
                "sort_code" => "kr",
                "name" => "Kanuri",
                "nativeName" => "Kanuri"
            ),
            array(
                "sort_code" => "ks",
                "name" => "Kashmiri",
                "nativeName" => "कश्मीरी, كشميري‎"
            ),
            array(
                "sort_code" => "kk",
                "name" => "Kazakh",
                "nativeName" => "Қазақ тілі"
            ),
            array(
                "sort_code" => "km",
                "name" => "Khmer",
                "nativeName" => "ភាសាខ្មែរ"
            ),
            array(
                "sort_code" => "ki",
                "name" => "Kikuyu, Gikuyu",
                "nativeName" => "Gĩkũyũ"
            ),
            array(
                "sort_code" => "rw",
                "name" => "Kinyarwanda",
                "nativeName" => "Ikinyarwanda"
            ),
            array(
                "sort_code" => "ky",
                "name" => "Kirghiz, Kyrgyz",
                "nativeName" => "кыргыз тили"
            ),
            array(
                "sort_code" => "kv",
                "name" => "Komi",
                "nativeName" => "коми кыв"
            ),
            array(
                "sort_code" => "kg",
                "name" => "Kongo",
                "nativeName" => "KiKongo"
            ),
            array(
                "sort_code" => "ko",
                "name" => "Korean",
                "nativeName" => "한국어 (韓國語), 조선말 (朝鮮語)"
            ),
            array(
                "sort_code" => "ku",
                "name" => "Kurdish",
                "nativeName" => "Kurdî, كوردی‎"
            ),
            array(
                "sort_code" => "kj",
                "name" => "Kwanyama, Kuanyama",
                "nativeName" => "Kuanyama"
            ),
            array(
                "sort_code" => "la",
                "name" => "Latin",
                "nativeName" => "latine, lingua latina"
            ),
            array(
                "sort_code" => "lb",
                "name" => "Luxembourgish, Letzeburgesch",
                "nativeName" => "Lëtzebuergesch"
            ),
            array(
                "sort_code" => "lg",
                "name" => "Luganda",
                "nativeName" => "Luganda"
            ),
            array(
                "sort_code" => "li",
                "name" => "Limburgish, Limburgan, Limburger",
                "nativeName" => "Limburgs"
            ),
            array(
                "sort_code" => "ln",
                "name" => "Lingala",
                "nativeName" => "Lingála"
            ),
            array(
                "sort_code" => "lo",
                "name" => "Lao",
                "nativeName" => "ພາສາລາວ"
            ),
            array(
                "sort_code" => "lt",
                "name" => "Lithuanian",
                "nativeName" => "lietuvių kalba"
            ),
            array(
                "sort_code" => "lu",
                "name" => "Luba-Katanga",
                "nativeName" => ""
            ),
            array(
                "sort_code" => "lv",
                "name" => "Latvian",
                "nativeName" => "latviešu valoda"
            ),
            array(
                "sort_code" => "gv",
                "name" => "Manx",
                "nativeName" => "Gaelg, Gailck"
            ),
            array(
                "sort_code" => "mk",
                "name" => "Macedonian",
                "nativeName" => "македонски јазик"
            ),
            array(
                "sort_code" => "mg",
                "name" => "Malagasy",
                "nativeName" => "Malagasy fiteny"
            ),
            array(
                "sort_code" => "ms",
                "name" => "Malay",
                "nativeName" => "bahasa Melayu, بهاس ملايو‎"
            ),
            array(
                "sort_code" => "ml",
                "name" => "Malayalam",
                "nativeName" => "മലയാളം"
            ),
            array(
                "sort_code" => "mt",
                "name" => "Maltese",
                "nativeName" => "Malti"
            ),
            array(
                "sort_code" => "mi",
                "name" => "Māori",
                "nativeName" => "te reo Māori"
            ),
            array(
                "sort_code" => "mr",
                "name" => "Marathi (Marāṭhī)",
                "nativeName" => "मराठी"
            ),
            array(
                "sort_code" => "mh",
                "name" => "Marshallese",
                "nativeName" => "Kajin M̧ajeļ"
            ),
            array(
                "sort_code" => "mn",
                "name" => "Mongolian",
                "nativeName" => "монгол"
            ),
            array(
                "sort_code" => "na",
                "name" => "Nauru",
                "nativeName" => "Ekakairũ Naoero"
            ),
            array(
                "sort_code" => "nv",
                "name" => "Navajo, Navaho",
                "nativeName" => "Diné bizaad, Dinékʼehǰí"
            ),
            array(
                "sort_code" => "nb",
                "name" => "Norwegian Bokmål",
                "nativeName" => "Norsk bokmål"
            ),
            array(
                "sort_code" => "nd",
                "name" => "North Ndebele",
                "nativeName" => "isiNdebele"
            ),
            array(
                "sort_code" => "ne",
                "name" => "Nepali",
                "nativeName" => "नेपाली"
            ),
            array(
                "sort_code" => "ng",
                "name" => "Ndonga",
                "nativeName" => "Owambo"
            ),
            array(
                "sort_code" => "nn",
                "name" => "Norwegian Nynorsk",
                "nativeName" => "Norsk nynorsk"
            ),
            array(
                "sort_code" => "no",
                "name" => "Norwegian",
                "nativeName" => "Norsk"
            ),
            array(
                "sort_code" => "ii",
                "name" => "Nuosu",
                "nativeName" => "ꆈꌠ꒿ Nuosuhxop"
            ),
            array(
                "sort_code" => "nr",
                "name" => "South Ndebele",
                "nativeName" => "isiNdebele"
            ),
            array(
                "sort_code" => "oc",
                "name" => "Occitan",
                "nativeName" => "Occitan"
            ),
            array(
                "sort_code" => "oj",
                "name" => "Ojibwe, Ojibwa",
                "nativeName" => "ᐊᓂᔑᓈᐯᒧᐎᓐ"
            ),
            array(
                "sort_code" => "cu",
                "name" => "Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic",
                "nativeName" => "ѩзыкъ словѣньскъ"
            ),
            array(
                "sort_code" => "om",
                "name" => "Oromo",
                "nativeName" => "Afaan Oromoo"
            ),
            array(
                "sort_code" => "or",
                "name" => "Oriya",
                "nativeName" => "ଓଡ଼ିଆ"
            ),
            array(
                "sort_code" => "os",
                "name" => "Ossetian, Ossetic",
                "nativeName" => "ирон æвзаг"
            ),
            array(
                "sort_code" => "pa",
                "name" => "Panjabi, Punjabi",
                "nativeName" => "ਪੰਜਾਬੀ, پنجابی‎"
            ),
            array(
                "sort_code" => "pi",
                "name" => "Pāli",
                "nativeName" => "पाऴि"
            ),
            array(
                "sort_code" => "fa",
                "name" => "Persian",
                "nativeName" => "فارسی"
            ),
            array(
                "sort_code" => "pl",
                "name" => "Polish",
                "nativeName" => "polski"
            ),
            array(
                "sort_code" => "ps",
                "name" => "Pashto, Pushto",
                "nativeName" => "پښتو"
            ),
            array(
                "sort_code" => "pt",
                "name" => "Portuguese",
                "nativeName" => "Português"
            ),
            array(
                "sort_code" => "qu",
                "name" => "Quechua",
                "nativeName" => "Runa Simi, Kichwa"
            ),
            array(
                "sort_code" => "rm",
                "name" => "Romansh",
                "nativeName" => "rumantsch grischun"
            ),
            array(
                "sort_code" => "rn",
                "name" => "Kirundi",
                "nativeName" => "kiRundi"
            ),
            array(
                "sort_code" => "ro",
                "name" => "Romanian, Moldavian, Moldovan",
                "nativeName" => "română"
            ),
            array(
                "sort_code" => "ru",
                "name" => "Russian",
                "nativeName" => "русский язык"
            ),
            array(
                "sort_code" => "sa",
                "name" => "Sanskrit (Saṁskṛta)",
                "nativeName" => "संस्कृतम्"
            ),
            array(
                "sort_code" => "sc",
                "name" => "Sardinian",
                "nativeName" => "sardu"
            ),
            array(
                "sort_code" => "sd",
                "name" => "Sindhi",
                "nativeName" => "सिन्धी, سنڌي، سندھی‎"
            ),
            array(
                "sort_code" => "se",
                "name" => "Northern Sami",
                "nativeName" => "Davvisámegiella"
            ),
            array(
                "sort_code" => "sm",
                "name" => "Samoan",
                "nativeName" => "gagana faa Samoa"
            ),
            array(
                "sort_code" => "sg",
                "name" => "Sango",
                "nativeName" => "yângâ tî sängö"
            ),
            array(
                "sort_code" => "sr",
                "name" => "Serbian",
                "nativeName" => "српски језик"
            ),
            array(
                "sort_code" => "gd",
                "name" => "Scottish Gaelic; Gaelic",
                "nativeName" => "Gàidhlig"
            ),
            array(
                "sort_code" => "sn",
                "name" => "Shona",
                "nativeName" => "chiShona"
            ),
            array(
                "sort_code" => "si",
                "name" => "Sinhala, Sinhalese",
                "nativeName" => "සිංහල"
            ),
            array(
                "sort_code" => "sk",
                "name" => "Slovak",
                "nativeName" => "slovenčina"
            ),
            array(
                "sort_code" => "sl",
                "name" => "Slovene",
                "nativeName" => "slovenščina"
            ),
            array(
                "sort_code" => "so",
                "name" => "Somali",
                "nativeName" => "Soomaaliga, af Soomaali"
            ),
            array(
                "sort_code" => "st",
                "name" => "Southern Sotho",
                "nativeName" => "Sesotho"
            ),
            array(
                "sort_code" => "es",
                "name" => "Spanish",
                "nativeName" => "español"
            ),
            array(
                "sort_code" => "su",
                "name" => "Sundanese",
                "nativeName" => "Basa Sunda"
            ),
            array(
                "sort_code" => "sw",
                "name" => "Swahili",
                "nativeName" => "Kiswahili"
            ),
            array(
                "sort_code" => "ss",
                "name" => "Swati",
                "nativeName" => "SiSwati"
            ),
            array(
                "sort_code" => "sv",
                "name" => "Swedish",
                "nativeName" => "svenska"
            ),
            array(
                "sort_code" => "ta",
                "name" => "Tamil",
                "nativeName" => "தமிழ்"
            ),
            array(
                "sort_code" => "te",
                "name" => "Telugu",
                "nativeName" => "తెలుగు"
            ),
            array(
                "sort_code" => "tg",
                "name" => "Tajik",
                "nativeName" => "тоҷикӣ, toğikī, تاجیکی‎"
            ),
            array(
                "sort_code" => "th",
                "name" => "Thai",
                "nativeName" => "ไทย"
            ),
            array(
                "sort_code" => "ti",
                "name" => "Tigrinya",
                "nativeName" => "ትግርኛ"
            ),
            array(
                "sort_code" => "bo",
                "name" => "Tibetan Standard, Tibetan, Central",
                "nativeName" => "བོད་ཡིག"
            ),
            array(
                "sort_code" => "tk",
                "name" => "Turkmen",
                "nativeName" => "Türkmen, Түркмен"
            ),
            array(
                "sort_code" => "tl",
                "name" => "Tagalog",
                "nativeName" => "Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔"
            ),
            array(
                "sort_code" => "tn",
                "name" => "Tswana",
                "nativeName" => "Setswana"
            ),
            array(
                "sort_code" => "to",
                "name" => "Tonga (Tonga Islands)",
                "nativeName" => "faka Tonga"
            ),
            array(
                "sort_code" => "tr",
                "name" => "Turkish",
                "nativeName" => "Türkçe"
            ),
            array(
                "sort_code" => "ts",
                "name" => "Tsonga",
                "nativeName" => "Xitsonga"
            ),
            array(
                "sort_code" => "tt",
                "name" => "Tatar",
                "nativeName" => "татарча, tatarça, تاتارچا‎"
            ),
            array(
                "sort_code" => "tw",
                "name" => "Twi",
                "nativeName" => "Twi"
            ),
            array(
                "sort_code" => "ty",
                "name" => "Tahitian",
                "nativeName" => "Reo Tahiti"
            ),
            array(
                "sort_code" => "ug",
                "name" => "Uighur, Uyghur",
                "nativeName" => "Uyƣurqə, ئۇيغۇرچە‎"
            ),
            array(
                "sort_code" => "uk",
                "name" => "Ukrainian",
                "nativeName" => "українська"
            ),
            array(
                "sort_code" => "ur",
                "name" => "Urdu",
                "nativeName" => "اردو"
            ),
            array(
                "sort_code" => "uz",
                "name" => "Uzbek",
                "nativeName" => "zbek, Ўзбек, أۇزبېك‎"
            ),
            array(
                "sort_code" => "ve",
                "name" => "Venda",
                "nativeName" => "Tshivenḓa"
            ),
            array(
                "sort_code" => "vi",
                "name" => "Vietnamese",
                "nativeName" => "Tiếng Việt"
            ),
            array(
                "sort_code" => "vo",
                "name" => "Volapük",
                "nativeName" => "Volapük"
            ),
            array(
                "sort_code" => "wa",
                "name" => "Walloon",
                "nativeName" => "Walon"
            ),
            array(
                "sort_code" => "cy",
                "name" => "Welsh",
                "nativeName" => "Cymraeg"
            ),
            array(
                "sort_code" => "wo",
                "name" => "Wolof",
                "nativeName" => "Wollof"
            ),
            array(
                "sort_code" => "fy",
                "name" => "Western Frisian",
                "nativeName" => "Frysk"
            ),
            array(
                "sort_code" => "xh",
                "name" => "Xhosa",
                "nativeName" => "isiXhosa"
            ),
            array(
                "sort_code" => "yi",
                "name" => "Yiddish",
                "nativeName" => "ייִדיש"
            ),
            array(
                "sort_code" => "yo",
                "name" => "Yoruba",
                "nativeName" => "Yorùbá"
            ),
            array(
                "sort_code" => "za",
                "name" => "Zhuang, Chuang",
                "nativeName" => "Saɯ cueŋƅ, Saw cuengh"
            )
        );

        \DB::table('languages')->insert($language);
    }
}

/*
array('name'=>'English','sort_sort_code'=>'en'),
                 array('name'=>'Hindi','sort_sort_code'=>'hi'),
                 array('name'=>'Italian','sort_sort_code'=>'it'),
                 array('name'=>'French','sort_sort_code'=>'fr']
                 */
