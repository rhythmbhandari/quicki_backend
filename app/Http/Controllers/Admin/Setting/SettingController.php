<?php

namespace App\Http\Controllers\Admin\Setting;

use Illuminate\Http\Request;
use App\Modules\Services\Setting\SettingService;
use App\Http\Controllers\BaseController;
use App\Modules\Models\Setting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Kamaln7\Toastr\Facades\Toastr;
use App\Rules\ValidImageRatio;

class SettingController extends Controller
{

    protected $district_list = '[
        {
          "Name": "Bhojpur",
          "Nepali": "भोजपुर जिल्ला",
          "Headquarters": "Bhojpur",
          "Population (2011)": "182,459",
          "Area(km2)": "1,507",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dhankuta",
          "Nepali": "धनकुटा जिल्ला",
          "Headquarters": "Dhankuta",
          "Population (2011)": "163,412",
          "Area(km2)": "0,892",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Ilam",
          "Nepali": "इलाम जिल्ला",
          "Headquarters": "Ilam",
          "Population (2011)": "290,254",
          "Area(km2)": "1,703",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Jhapa",
          "Nepali": "झापा जिल्ला",
          "Headquarters": "Bhadrapur",
          "Population (2011)": "812,650",
          "Area(km2)": "1,606",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Khotang",
          "Nepali": "खोटाँग जिल्ला",
          "Headquarters": "Diktel",
          "Population (2011)": "206,312",
          "Area(km2)": "1,591",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Morang",
          "Nepali": "मोरंग जिल्ला",
          "Headquarters": "Biratnagar",
          "Population (2011)": "965,370",
          "Area(km2)": "1,855",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality", "Metropolitan"]
        },
        {
          "Name": "Okhaldhunga",
          "Nepali": "ओखलढुंगा जिल्ला",
          "Headquarters": "Siddhicharan",
          "Population (2011)": "147,984",
          "Area(km2)": "1,074",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Panchthar",
          "Nepali": "पांचथर जिल्ला",
          "Headquarters": "Phidim",
          "Population (2011)": "191,817",
          "Area(km2)": "1,241",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Sankhuwasabha",
          "Nepali": "संखुवासभा जिल्ला",
          "Headquarters": "Khandbari",
          "Population (2011)": "158,742",
          "Area(km2)": "3,480",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Solukhumbu",
          "Nepali": "सोलुखुम्बू जिल्ला",
          "Headquarters": "Salleri",
          "Population (2011)": "105,886",
          "Area(km2)": "3,312",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Sunsari",
          "Nepali": "सुनसरी जिल्ला",
          "Headquarters": "Inaruwa",
          "Population (2011)": "763,497",
          "Area(km2)": "1,257",
          "Province": "Province No. 1",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Taplejung",
          "Nepali": "ताप्लेजुंग जिल्ला",
          "Headquarters": "Taplejung",
          "Population (2011)": "127,461",
          "Area(km2)": "3,646",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Terhathum",
          "Nepali": "तेह्रथुम जिल्ला",
          "Headquarters": "Myanglung",
          "Population (2011)": "113,111",
          "Area(km2)": "0,679",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Udayapur",
          "Nepali": "उदयपुर जिल्ला",
          "Headquarters": "Gaighat",
          "Population (2011)": "317,532",
          "Area(km2)": "2,063",
          "Province": "Province No. 1",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Saptari",
          "Nepali": "सप्तरी जिल्ला",
          "Headquarters": "Rajbiraj",
          "Population (2011)": "639,284",
          "Area(km2)": "1,363",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Parsa",
          "Nepali": "पर्सा जिल्ला",
          "Headquarters": "Birgunj",
          "Population (2011)": "601,017",
          "Area(km2)": "1,353",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality", "Metropolitan"]
        },
        {
          "Name": "Sarlahi",
          "Nepali": "सर्लाही जिल्ला",
          "Headquarters": "Malangwa",
          "Population (2011)": "769,729",
          "Area(km2)": "1,259",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Bara",
          "Nepali": "बारा जिल्ला",
          "Headquarters": "Kalaiya",
          "Population (2011)": "687,708",
          "Area(km2)": "1,190",
          "Province": "Province No. 2",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Siraha",
          "Nepali": "सिराहा जिल्ला",
          "Headquarters": "Siraha",
          "Population (2011)": "637,328",
          "Area(km2)": "1,188",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dhanusha",
          "Nepali": "धनुषा जिल्ला",
          "Headquarters": "Janakpur",
          "Population (2011)": "754,777",
          "Area(km2)": "1,180",
          "Province": "Province No. 2",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Rautahat",
          "Nepali": "रौतहट जिल्ला",
          "Headquarters": "Gaur",
          "Population (2011)": "686,722",
          "Area(km2)": "1,126",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Mahottari",
          "Nepali": "महोत्तरी जिल्ला",
          "Headquarters": "Jaleshwar",
          "Population (2011)": "627,580",
          "Area(km2)": "1,002",
          "Province": "Province No. 2",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Sindhuli",
          "Nepali": "सिन्धुली जिल्ला",
          "Headquarters": "Kamalamai",
          "Area(km2)": "2,491",
          "Population (2011)": "296,192",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Ramechhap",
          "Nepali": "रामेछाप जिल्ला",
          "Headquarters": "Manthali",
          "Area(km2)": "1,546",
          "Population (2011)": "202,646",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dolakha",
          "Nepali": "दोलखा जिल्ला",
          "Headquarters": "Bhimeshwar",
          "Area(km2)": "2,191",
          "Population (2011)": "186,557",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Bhaktapur",
          "Nepali": "भक्तपुर जिल्ला",
          "Headquarters": "Bhaktapur",
          "Area(km2)": 119,
          "Population (2011)": "304,651",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality"]
        },
        {
          "Name": "Dhading",
          "Nepali": "धादिङ जिल्ला",
          "Headquarters": "Nilkantha",
          "Area(km2)": "1,926",
          "Population (2011)": "336,067",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kathmandu",
          "Nepali": "काठमाडौँ जिल्ला",
          "Headquarters": "Kathmandu",
          "Area(km2)": 395,
          "Population (2011)": "1,744,240",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Metropolitan"]
        },
        {
          "Name": "Kavrepalanchok",
          "Nepali": "काभ्रेपलान्चोक जिल्ला",
          "Headquarters": "Dhulikhel",
          "Area(km2)": "1,396",
          "Population (2011)": "381,937",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Lalitpur",
          "Nepali": "ललितपुर जिल्ला",
          "Headquarters": "Lalitpur",
          "Area(km2)": 385,
          "Population (2011)": "468,132",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality", "Metropolitan"]
        },
        {
          "Name": "Nuwakot",
          "Nepali": "नुवाकोट जिल्ला",
          "Headquarters": "Bidur",
          "Area(km2)": "1,121",
          "Population (2011)": "277,471",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Rasuwa",
          "Nepali": "रसुवा जिल्ला",
          "Headquarters": "Dhunche",
          "Area(km2)": "1,544",
          "Population (2011)": "43,300",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Sindhupalchok",
          "Nepali": "सिन्धुपाल्चोक जिल्ला",
          "Headquarters": "Chautara",
          "Area(km2)": "2,542",
          "Population (2011)": "287,798",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Chitwan",
          "Nepali": "चितवन जिल्ला",
          "Headquarters": "Bharatpur",
          "Area(km2)": "2,218",
          "Population (2011)": "579,984",
          "Province": "Province No. 3",
          "LocalBodiesType": ["Municipality", "Rular Municipality", "Metropolitan"]
        },
        {
          "Name": "Makwanpur",
          "Nepali": "मकवानपुर जिल्ला",
          "Headquarters": "Hetauda",
          "Area(km2)": "2,426",
          "Population (2011)": "420,477",
          "Province": "Province No. 3",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Baglung",
          "Nepali": "बागलुङ जिल्ला",
          "Headquarters": "Baglung",
          "Area(km2)": "1,784",
          "Population (2011)": "268,613",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Gorkha",
          "Nepali": "गोरखा जिल्ला",
          "Headquarters": "Gorkha",
          "Area(km2)": "3,610",
          "Population (2011)": "271,061",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kaski",
          "Nepali": "कास्की जिल्ला",
          "Headquarters": "Pokhara",
          "Area(km2)": "2,017",
          "Population (2011)": "492,098",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality", "Metropolitan"]
        },
        {
          "Name": "Lamjung",
          "Nepali": "लमजुङ जिल्ला",
          "Headquarters": "Besisahar",
          "Area(km2)": "1,692",
          "Population (2011)": "167,724",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Manang",
          "Nepali": "मनाङ जिल्ला",
          "Headquarters": "Chame",
          "Area(km2)": "2,246",
          "Population (2011)": "6,538",
          "Province": "Gandaki",
          "LocalBodiesType": ["Rular Municipality"]
        },
        {
          "Name": "Mustang",
          "Nepali": "मुस्ताङ जिल्ला",
          "Headquarters": "Jomsom",
          "Area(km2)": "3,573",
          "Population (2011)": "13,452",
          "Province": "Gandaki",
          "LocalBodiesType": ["Rular Municipality"]
        },
        {
          "Name": "Myagdi",
          "Nepali": "म्याग्दी जिल्ला",
          "Headquarters": "Beni",
          "Area(km2)": "2,297",
          "Population (2011)": "113,641",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Nawalpur",
          "Nepali": "नवलपुर जिल्ला",
          "Headquarters": "Kawasoti",
          "Area(km2)": "1,043.1",
          "Population (2011)": "310,864",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Parbat",
          "Nepali": "पर्वत जिल्ला",
          "Headquarters": "Kusma",
          "Area(km2)": 494,
          "Population (2011)": "146,590",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Syangja",
          "Nepali": "स्याङग्जा जिल्ला",
          "Headquarters": "Putalibazar",
          "Area(km2)": "1,164",
          "Population (2011)": "289,148",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Tanahun",
          "Nepali": "तनहुँ जिल्ला",
          "Headquarters": "Damauli",
          "Area(km2)": "1,546",
          "Population (2011)": "323,288",
          "Province": "Gandaki",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kapilvastu",
          "Nepali": "कपिलवस्तु जिल्ला",
          "Headquarters": "Taulihawa",
          "Area(km2)": "1,738",
          "Population (2011)": "571,936",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Parasi",
          "Nepali": "परासी जिल्ला",
          "Headquarters": "Ramgram",
          "Area(km2)": 634.88,
          "Population (2011)": "321,058",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Rupandehi",
          "Nepali": "रुपन्देही जिल्ला",
          "Headquarters": "Siddharthanagar",
          "Area(km2)": "1,360",
          "Population (2011)": "880,196",
          "Province": "Province No. 5",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Arghakhanchi",
          "Nepali": "अर्घाखाँची जिल्ला",
          "Headquarters": "Sandhikharka",
          "Area(km2)": "1,193",
          "Population (2011)": "197,632",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Gulmi",
          "Nepali": "गुल्मी जिल्ला",
          "Headquarters": "Tamghas",
          "Area(km2)": "1,149",
          "Population (2011)": "280,160",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Palpa",
          "Nepali": "पाल्पा जिल्ला",
          "Headquarters": "Tansen",
          "Area(km2)": "1,373",
          "Population (2011)": "261,180",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dang",
          "Nepali": "दाङ जिल्ला",
          "Headquarters": "Ghorahi",
          "Area(km2)": "2,955",
          "Population (2011)": "552,583",
          "Province": "Province No. 5",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Pyuthan",
          "Nepali": "प्युठान जिल्ला",
          "Headquarters": "Pyuthan",
          "Area(km2)": "1,309",
          "Population (2011)": "228,102",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Rolpa",
          "Nepali": "रोल्पा जिल्ला",
          "Headquarters": "Liwang",
          "Area(km2)": "1,879",
          "Population (2011)": "224,506",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Eastern Rukum",
          "Nepali": "पूर्वी रूकुम जिल्ला",
          "Headquarters": "Rukumkot",
          "Area(km2)": "1,161.13",
          "Population (2011)": "53,018",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Banke",
          "Nepali": "बाँके जिल्ला",
          "Headquarters": "Nepalganj",
          "Area(km2)": "2,337",
          "Population (2011)": "491,313",
          "Province": "Province No. 5",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Bardiya",
          "Nepali": "बर्दिया जिल्ला",
          "Headquarters": "Gulariya",
          "Area(km2)": "2,025",
          "Population (2011)": "426,576",
          "Province": "Province No. 5",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Western Rukum",
          "Nepali": "पश्चिमी रूकुम जिल्ला",
          "Headquarters": "Musikot",
          "Area(km2)": "1,213.49",
          "Population (2011)": "154,272",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Salyan",
          "Nepali": "सल्यान जिल्ला",
          "Headquarters": "Salyan",
          "Area(km2)": "1,462",
          "Population (2011)": "242,444",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dolpa",
          "Nepali": "डोल्पा जिल्ला",
          "Headquarters": "Dunai",
          "Area(km2)": "7,889",
          "Population (2011)": "36,700",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Humla",
          "Nepali": "हुम्ला जिल्ला",
          "Headquarters": "Simikot",
          "Area(km2)": "5,655",
          "Population (2011)": "50,858",
          "Province": "Karnali",
          "LocalBodiesType": ["Rular Municipality"]
        },
        {
          "Name": "Jumla",
          "Nepali": "जुम्ला जिल्ला",
          "Headquarters": "Chandannath",
          "Area(km2)": "2,531",
          "Population (2011)": "108,921",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kalikot",
          "Nepali": "कालिकोट जिल्ला",
          "Headquarters": "Manma",
          "Area(km2)": "1,741",
          "Population (2011)": "136,948",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Mugu",
          "Nepali": "मुगु जिल्ला",
          "Headquarters": "Gamgadhi",
          "Area(km2)": "3,535",
          "Population (2011)": "55,286",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Surkhet",
          "Nepali": "सुर्खेत जिल्ला",
          "Headquarters": "Birendranagar",
          "Area(km2)": "2,451",
          "Population (2011)": "350,804",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dailekh",
          "Nepali": "दैलेख जिल्ला",
          "Headquarters": "Narayan",
          "Area(km2)": "1,502",
          "Population (2011)": "261,770",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Jajarkot",
          "Nepali": "जाजरकोट जिल्ला",
          "Headquarters": "Khalanga",
          "Area(km2)": "2,230",
          "Population (2011)": "171,304",
          "Province": "Karnali",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kailali",
          "Nepali": "कैलाली जिल्ला",
          "Headquarters": "Dhangadhi",
          "Area(km2)": "3,235",
          "Population (2011)": "775,709",
          "Province": "SudurPaschim",
          "LocalBodiesType": [
            "Municipality",
            "Rular Municipality",
            "Sub-Metropolitan"
          ]
        },
        {
          "Name": "Achham",
          "Nepali": "अछाम जिल्ला",
          "Headquarters": "Mangalsen",
          "Area(km2)": "1,680",
          "Population (2011)": "257,477",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Doti",
          "Nepali": "डोटी जिल्ला",
          "Headquarters": "Dipayal Silgadhi",
          "Area(km2)": "2,025",
          "Population (2011)": "211,746",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Bajhang",
          "Nepali": "बझाङ जिल्ला",
          "Headquarters": "Jayaprithvi",
          "Area(km2)": "3,422",
          "Population (2011)": "195,159",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Bajura",
          "Nepali": "बाजुरा जिल्ला",
          "Headquarters": "Martadi",
          "Area(km2)": "2,188",
          "Population (2011)": "134,912",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Kanchanpur",
          "Nepali": "कंचनपुर जिल्ला",
          "Headquarters": "Bhimdatta",
          "Area(km2)": "1,610",
          "Population (2011)": "451,248",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Dadeldhura",
          "Nepali": "डडेलधुरा जिल्ला",
          "Headquarters": "Amargadhi",
          "Area(km2)": "1,538",
          "Population (2011)": "142,094",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Baitadi",
          "Nepali": "बैतडी जिल्ला",
          "Headquarters": "Dasharathchand",
          "Area(km2)": "1,519",
          "Population (2011)": "250,898",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        },
        {
          "Name": "Darchula",
          "Nepali": "दार्चुला जिल्ला",
          "Headquarters": "Darchula",
          "Area(km2)": "2,322",
          "Population (2011)": "133,274",
          "Province": "SudurPaschim",
          "LocalBodiesType": ["Municipality", "Rular Municipality"]
        }
      ]';
    
    protected $setting;

    function __construct(SettingService $setting)
    {
        $this->setting = $setting;
    }


    /**
     * Firstly we are setting the page title and subtitle, then returning the settings index view.
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $districts = array_column(json_decode($this->district_list),'Name');
        //dd("DISTRICTS: ",$districts);

        //$this->setPageTitle('Settings', 'Manage Settings');
        $settings = Setting::all();
        $setting_groups =   DB::table('settings')
        ->select('group')
        ->groupBy('group')
        ->orderBy('created_by','asc')
        ->get()->toArray();
       // dd($setting_groups[0]->group);
        return view('admin.setting.index', compact('settings', 'setting_groups', 'districts'));
    }

   


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Setting $setting)
    {
        //dd("REQUEST: ", $request->all(), "SETTING: ", $setting->toArray());

       // dd("validation String: ",$this->setting->getValidationString($setting->data_type, $setting->required));

       //dd($uploaded_image_ratio);
       //$uploaded_image_width = $request->value->path()[0];
      // dd(getimagesize($request->value->path()));
        $request->validate([
            'value' =>  $this->setting->getValidationString($setting,  $request->value),
            ],
            [
                'value.required' => getSpacedTextAttribute($setting->key).' field is required!', 
                //'value.dimensions' =>  getSpacedTextAttribute($setting->key).' must have a ratio of '.(( isset($setting->image_ratio) && !empty($setting->image_ratio) ) ? $setting->image_ratio:'1!'), 
            ]
        );


        if($this->setting->update($setting->id,  $request->all() )){     //$request->all()
           // dd($request->all(), "FILE IMAGE", $request->hasFile('value'));
            if ($request->hasFile('value')) {
                $this->uploadFile($request, $setting);
            }
         
            Toastr::success( getSpacedTextAttribute($setting->key).' updated successfully.', 'Success !!!', ["positionClass" => "toast-top-right", "newestOnTop" => "true" , "closeButton" => "true", "progressBar" => "true", "showDuration" => "300", "hideDuration" => "1000", "timeOut" => "5000", "extendedTimeOut" => "1000", "showEasing" => "swing", "showMethod" => "fadeIn", "hideMethod" => "fadeOut"]);
            return redirect()->route('admin.setting.index');
        } 
    
        Toastr::error(getSpacedTextAttribute($setting->key).'  could not be updated.', 'Oops !!!', ["positionClass" => "toast-top-right", "newestOnTop" => "true" , "closeButton" => "true", "progressBar" => "true", "showDuration" => "300", "hideDuration" => "1000", "timeOut" => "5000", "extendedTimeOut" => "1000", "showEasing" => "swing", "showMethod" => "fadeIn", "hideMethod" => "fadeOut"]);
        return redirect()->route('admin.setting.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy(Setting $setting)
    {
        //
    }


    public function getSettingContent(Setting $setting)
    {
      $districts = array_column(json_decode($this->district_list),'Name');
        //dd("SETTING SELEC: ", $setting);
        return view('admin.setting.includes.contentSection', compact('setting','districts'))->render();
    }

    public function updateSetting(Request $request, Setting $setting)
    {
        //dd("REQUEST: ", $request->all(),"SETTING: ", $setting->toArray());
        $data_type = $setting->data_type;

        $value_validation_string = "";
        $required = ($setting->required == 1)?"required|":"";
        if($data_type == "text")
        {
            $value_validation_string =  $required."";
        }
        else if($data_type == "integer" )
        {
            $value_validation_string = $required."integer";
        }
        else if($data_type == "double" || $data_type == "number" || $data_type == "numeric")
        {
            $value_validation_string = $required."numeric";   
        }
        else if($data_type == "image")
        {
            //|image|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=1000,max_height=1000'
            $value_validation_string = $required."image"; //|mimes:jpg,png,jpeg,gif,svg|max:2048|dimensions:min_width=100,min_height=100,max_width=2500,max_height=2500"
        }
        else if($data_type == "icon")
        {
            $value_validation_string = $required."image";
        }
        else if($data_type == "link")
        {
            $value_validation_string = $required."";
        }
        else if($data_type == "email")
        {
            $value_validation_string = $required."email";
        }
        else if($data_type == "json")
        {
            $value_validation_string = $required."json";
        }
        else{
            //do nothin
        }

        $validated = $request->validate([
            'value' => $value_validation_string,
        ]);

     //   dd("VALIDATED SUCCESS", $validated);

    }




    
    //function uploadFile(Request $request, $product)
    function uploadFile(Request $request, $setting)
    {
        $file = $request->file('value');
        $fileName = $this->setting->uploadFile($file);
        if (!empty($setting->value))
            $this->setting->__deleteImages($setting);

        $data['value'] = $fileName;
        $this->setting->updateImage($setting->id, $data);
    }




    function loadSettingForms($group)
    {
      $districts = array_column(json_decode($this->district_list),'Name');
        $settings = Setting::where('group', $group)->get();
        //dd($settings->toArray());
        return view('admin.setting.includes.settingFormsSection', compact('settings','districts'))->render();
    }

}
