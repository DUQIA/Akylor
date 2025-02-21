window.onload = function() {
  // 语言切换
  const lang = {
    "zh-CN": {
      flow: '流量',
      download: '下载',
      upload: '上传',
      resource: '资源',
      flowMap: '流量地图',
      countrys: {
        'Somalia' : '索马里',
        'Liechtenstein' : '列支敦士登',
        'Morocco' : '摩洛哥',
        'W. Sahara' : '西撒哈拉',
        'Serbia' : '塞尔维亚',
        'Afghanistan' : '阿富汗',
        'Angola' : '安哥拉',
        'Albania' : '阿尔巴尼亚',
        'Aland' : '奥兰',
        'Andorra' : '安道尔',
        'United Arab Emirates' : '阿拉伯联合酋长国',
        'Argentina' : '阿根廷',
        'Armenia' : '亚美尼亚',
        'American Samoa' : '美属萨摩亚',
        'Fr. S. Antarctic Lands' : '南极大陆',
        'Antigua and Barb.' : '安提瓜和巴布达',
        'Australia' : '澳大利亚',
        'Austria' : '奥地利',
        'Azerbaijan' : '阿塞拜疆',
        'Burundi' : '布隆迪',
        'Belgium' : '比利时',
        'Benin' : '贝宁',
        'Burkina Faso' : '布基纳法索',
        'Bangladesh' : '孟加拉国',
        'Bulgaria' : '保加利亚',
        'Bahrain' : '巴林',
        'Bahamas' : '巴哈马',
        'Bosnia and Herz.' : '波斯尼亚和赫兹',
        'Belarus' : '白俄罗斯',
        'Belize' : '伯利兹',
        'Bermuda' : '百慕大',
        'Bolivia' : '玻利维亚',
        'Brazil' : '巴西',
        'Barbados' : '巴巴多斯',
        'Brunei' : '文莱',
        'Bhutan' : '不丹',
        'Botswana' : '博茨瓦纳',
        'Central African Rep.' : '中非共和国',
        'Canada' : '加拿大',
        'Switzerland' : '瑞士',
        'Chile' : '智利',
        'China' : '中国',
        "Côte d'Ivoire" : '科特迪瓦',
        'Cameroon' : '喀麦隆',
        'Dem. Rep. Congo' : '民主党刚果共和国',
        'Congo' : '刚果',
        'Colombia' : '哥伦比亚',
        'Comoros' : '科摩罗',
        'Cape Verde' : '佛得角',
        'Costa Rica' : '哥斯达黎加',
        'Cuba' : '古巴',
        'Curaçao' : '库拉索岛',
        'Cayman Is.' : '开曼群岛北塞浦路斯',
        'N. Cyprus' : '塞浦路斯',
        'Cyprus' : '塞浦路斯',
        'Czech Rep.' : '捷克共和国',
        'Germany' : '德国',
        'Djibouti' : '吉布提',
        'Dominica' : '多米尼加',
        'Denmark' : '丹麦',
        'Dominican Rep.' : '多米尼加共和国',
        'Algeria' : '阿尔及利亚',
        'Ecuador' : '厄瓜多尔',
        'Egypt' : '埃及',
        'Eritrea' : '厄立特里亚',
        'Spain' : '西班牙',
        'Estonia' : '爱沙尼亚',
        'Ethiopia' : '埃塞俄比亚',
        'Finland' : '芬兰',
        'Fiji' : '斐济',
        'Falkland Is.' : '福克兰群岛',
        'France' : '法国',
        'Faeroe Is.' : '法罗群岛',
        'Micronesia' : '密克罗尼西亚',
        'Gabon' : '加蓬',
        'United Kingdom' : '英国',
        'Georgia' : '格鲁吉亚',
        'Ghana' : '加纳',
        'Guinea' : '几内亚',
        'Gambia' : '冈比亚',
        'Guinea-Bissau' : '几内亚比绍',
        'Eq. Guinea' : '几内亚',
        'Greece' : '希腊',
        'Grenada' : '格林纳达',
        'Greenland' : '格陵兰岛',
        'Guatemala' : '危地马拉',
        'Guam' : '关岛',
        'Guyana' : '圭亚那',
        'Heard I. and McDonald Is.' : '赫德岛和麦克唐纳群岛',
        'Honduras' : '洪都拉斯',
        'Croatia' : '克罗地亚',
        'Haiti' : '海地',
        'Hungary' : '匈牙利',
        'Indonesia' : '印度尼西亚',
        'Isle of Man' : '马恩岛',
        'India' : '印度',
        'Br. Indian Ocean Ter.' : '印度洋',
        'Ireland' : '爱尔兰',
        'Iran' : '伊朗',
        'Iraq' : '伊拉克',
        'Iceland' : '冰岛',
        'Israel' : '以色列',
        'Italy' : '意大利',
        'Jamaica' : '牙买加',
        'Jersey' : '泽西岛',
        'Jordan' : '约旦',
        'Japan' : '日本',
        'Siachen Glacier' : '锡亚琴冰川',
        'Kazakhstan' : '哈萨克斯坦',
        'Kenya' : '肯尼亚',
        'Kyrgyzstan' : '吉尔吉斯斯坦',
        'Cambodia' : '柬埔寨',
        'Kiribati' : '基里巴斯',
        'Korea' : '韩国',
        'Kuwait' : '科威特',
        'Lao PDR' : '老挝人民民主共和国',
        'Lebanon' : '黎巴嫩',
        'Liberia' : '利比里亚',
        'Libya' : '利比亚',
        'Saint Lucia' : '圣卢西亚',
        'Sri Lanka' : '斯里兰卡',
        'Lesotho' : '莱索托',
        'Lithuania' : '立陶宛',
        'Luxembourg' : '卢森堡',
        'Latvia' : '拉脱维亚',
        'Moldova' : '摩尔多瓦',
        'Madagascar' : '马达加斯加',
        'Mexico' : '墨西哥',
        'Macedonia' : '马其顿',
        'Mali' : '马里',
        'Malta' : '马耳他',
        'Myanmar' : '缅甸',
        'Montenegro' : '黑山',
        'Mongolia' : '蒙古',
        'N. Mariana Is.' : '北马里亚纳群岛',
        'Mozambique' : '莫桑比克',
        'Mauritania' : '毛里塔尼亚',
        'Montserrat' : '蒙特塞拉特岛',
        'Mauritius' : '毛里求斯',
        'Malawi' : '马拉维',
        'Malaysia' : '马来西亚',
        'Namibia' : '纳米比亚',
        'New Caledonia' : '新喀里多尼亚',
        'Niger' : '尼日尔',
        'Nigeria' : '尼日利亚',
        'Nicaragua' : '尼加拉瓜',
        'Niue' : '纽埃',
        'Netherlands' : '荷兰',
        'Norway' : '挪威',
        'Nepal' : '尼泊尔',
        'New Zealand' : '新西兰',
        'Oman' : '阿曼',
        'Pakistan' : '巴基斯坦',
        'Panama' : '巴拿马',
        'Peru' : '秘鲁',
        'Philippines' : '菲律宾',
        'Palau' : '帕劳',
        'Papua New Guinea' : '巴布亚新几内亚',
        'Poland' : '波兰',
        'Puerto Rico' : '波多黎各民主党',
        'Dem. Rep. Korea' : '韩国',
        'Portugal' : '葡萄牙',
        'Paraguay' : '巴拉圭',
        'Palestine' : '巴勒斯坦',
        'Fr. Polynesia' : '玻里尼西亚',
        'Qatar' : '卡塔尔',
        'Romania' : '罗马尼亚',
        'Russia' : '俄罗斯',
        'Rwanda' : '卢旺达',
        'Saudi Arabia' : '沙特阿拉伯',
        'Sudan' : '苏丹',
        'S. Sudan' : '南苏丹',
        'Senegal' : '塞内加尔',
        'Singapore' : '新加坡',
        'S. Geo. and S. Sandw. Is.' : '南乔治亚和南桑威奇群岛',
        'Saint Helena' : '圣赫勒拿',
        'Solomon Is.' : '所罗门群岛',
        'Sierra Leone' : '塞拉利昂',
        'El Salvador' : '萨尔瓦多',
        'St. Pierre and Miquelon' : '圣皮埃尔和密克隆群岛',
        'São Tomé and Principe' : '圣多美和普林西比',
        'Suriname' : '苏里南',
        'Slovakia' : '斯洛伐克',
        'Slovenia' : '斯洛文尼亚',
        'Sweden' : '瑞典',
        'Swaziland' : '斯威士兰',
        'Seychelles' : '塞舌尔',
        'Syria' : '叙利亚',
        'Turks and Caicos Is.' : '特克斯和凯科斯群岛',
        'Chad' : '乍得',
        'Togo' : '多哥',
        'Thailand' : '泰国',
        'Tajikistan' : '塔吉克斯坦',
        'Turkmenistan' : '土库曼斯坦',
        'Timor-Leste' : '东帝汶',
        'Tonga' : '汤加',
        'Trinidad and Tobago' : '特立尼达和多巴哥',
        'Tunisia' : '突尼斯',
        'Turkey' : '土耳其',
        'Tanzania' : '坦桑尼亚',
        'Uganda' : '乌干达',
        'Ukraine' : '乌克兰',
        'Uruguay' : '乌拉圭',
        'United States' : '美国',
        'Uzbekistan' : '乌兹别克斯坦',
        'St. Vin. and Gren.' : '圣文森特和格林纳丁斯',
        'Venezuela' : '委内瑞拉',
        'U.S. Virgin Is.' : '美属维尔京群岛',
        'Vietnam' : '越南',
        'Vanuatu' : '瓦努阿图',
        'Samoa' : '萨摩亚',
        'Yemen' : '也门',
        'South Africa' : '南非',
        'Zambia' : '赞比亚',
        'Zimbabwe' : '津巴布韦'
      }
    },
    "en": {
      flow: 'Flow',
      download: 'Download',
      upload: 'Upload',
      resource: 'Resource',
      flowMap: 'Flow map',
      countrys: {
        'Somalia' : 'Somalia',
        'Liechtenstein' : 'Liechtenstein',
        'Morocco' : 'Morocco',
        'W. Sahara' : 'W. Sahara',
        'Serbia' : 'Serbia',
        'Afghanistan' : 'Afghanistan',
        'Angola' : 'Angola',
        'Albania' : 'Albania',
        'Aland' : 'Aland',
        'Andorra' : 'Andorra',
        'United Arab Emirates' : 'United Arab Emirates',
        'Argentina' : 'Argentina',
        'Armenia' : 'Armenia',
        'American Samoa' : 'American Samoa',
        'Fr. S. Antarctic Lands' : 'Fr. S. Antarctic Lands',
        'Antigua and Barb.' : 'Antigua and Barb.',
        'Australia' : 'Australia',
        'Austria' : 'Austria',
        'Azerbaijan' : 'Azerbaijan',
        'Burundi' : 'Burundi',
        'Belgium' : 'Belgium',
        'Benin' : 'Benin',
        'Burkina Faso' : 'Burkina Faso',
        'Bangladesh' : 'Bangladesh',
        'Bulgaria' : 'Bulgaria',
        'Bahrain' : 'Bahrain',
        'Bahamas' : 'Bahamas',
        'Bosnia and Herz.' : 'Bosnia and Herz.',
        'Belarus' : 'Belarus',
        'Belize' : 'Belize',
        'Bermuda' : 'Bermuda',
        'Bolivia' : 'Bolivia',
        'Brazil' : 'Brazil',
        'Barbados' : 'Barbados',
        'Brunei' : 'Brunei',
        'Bhutan' : 'Bhutan',
        'Botswana' : 'Botswana',
        'Central African Rep.' : 'Central African Rep.',
        'Canada' : 'Canada',
        'Switzerland' : 'Switzerland',
        'Chile' : 'Chile',
        'China' : 'China',
        "Côte d'Ivoire" : "Côte d'Ivoire",
        'Cameroon' : 'Cameroon',
        'Dem. Rep. Congo' : 'Dem. Rep. Congo',
        'Congo' : 'Congo',
        'Colombia' : 'Colombia',
        'Comoros' : 'Comoros',
        'Cape Verde' : 'Cape Verde',
        'Costa Rica' : 'Costa Rica',
        'Cuba' : 'Cuba',
        'Curaçao' : 'Curaçao',
        'Cayman Is.' : 'Cayman Is.',
        'N. Cyprus' : 'N. Cyprus',
        'Cyprus' : 'Cyprus',
        'Czech Rep.' : 'Czech Rep.',
        'Germany' : 'Germany',
        'Djibouti' : 'Djibouti',
        'Dominica' : 'Dominica',
        'Denmark' : 'Denmark',
        'Dominican Rep.' : 'Dominican Rep.',
        'Algeria' : 'Algeria',
        'Ecuador' : 'Ecuador',
        'Egypt' : 'Egypt',
        'Eritrea' : 'Eritrea',
        'Spain' : 'Spain',
        'Estonia' : 'Estonia',
        'Ethiopia' : 'Ethiopia',
        'Finland' : 'Finland',
        'Fiji' : 'Fiji',
        'Falkland Is.' : 'Falkland Is.',
        'France' : 'France',
        'Faeroe Is.' : 'Faeroe Is.',
        'Micronesia' : 'Micronesia',
        'Gabon' : 'Gabon',
        'United Kingdom' : 'United Kingdom',
        'Georgia' : 'Georgia',
        'Ghana' : 'Ghana',
        'Guinea' : 'Guinea',
        'Gambia' : 'Gambia',
        'Guinea-Bissau' : 'Guinea-Bissau',
        'Eq. Guinea' : 'Eq. Guinea',
        'Greece' : 'Greece',
        'Grenada' : 'Grenada',
        'Greenland' : 'Greenland',
        'Guatemala' : 'Guatemala',
        'Guam' : 'Guam',
        'Guyana' : 'Guyana',
        'Heard I. and McDonald Is.' : 'Heard I. and McDonald Is.',
        'Honduras' : 'Honduras',
        'Croatia' : 'Croatia',
        'Haiti' : 'Haiti',
        'Hungary' : 'Hungary',
        'Indonesia' : 'Indonesia',
        'Isle of Man' : 'Isle of Man',
        'India' : 'India',
        'Br. Indian Ocean Ter.' : 'Br. Indian Ocean Ter.',
        'Ireland' : 'Ireland',
        'Iran' : 'Iran',
        'Iraq' : 'Iraq',
        'Iceland' : 'Iceland',
        'Israel' : 'Israel',
        'Italy' : 'Italy',
        'Jamaica' : 'Jamaica',
        'Jersey' : 'Jersey',
        'Jordan' : 'Jordan',
        'Japan' : 'Japan',
        'Siachen Glacier' : 'Siachen Glacier',
        'Kazakhstan' : 'Kazakhstan',
        'Kenya' : 'Kenya',
        'Kyrgyzstan' : 'Kyrgyzstan',
        'Cambodia' : 'Cambodia',
        'Kiribati' : 'Kiribati',
        'Korea' : 'Korea',
        'Kuwait' : 'Kuwait',
        'Lao PDR' : 'Lao PDR',
        'Lebanon' : 'Lebanon',
        'Liberia' : 'Liberia',
        'Libya' : 'Libya',
        'Saint Lucia' : 'Saint Lucia',
        'Sri Lanka' : 'Sri Lanka',
        'Lesotho' : 'Lesotho',
        'Lithuania' : 'Lithuania',
        'Luxembourg' : 'Luxembourg',
        'Latvia' : 'Latvia',
        'Moldova' : 'Moldova',
        'Madagascar' : 'Madagascar',
        'Mexico' : 'Mexico',
        'Macedonia' : 'Macedonia',
        'Mali' : 'Mali',
        'Malta' : 'Malta',
        'Myanmar' : 'Myanmar',
        'Montenegro' : 'Montenegro',
        'Mongolia' : 'Mongolia',
        'N. Mariana Is.' : 'N. Mariana Is.',
        'Mozambique' : 'Mozambique',
        'Mauritania' : 'Mauritania',
        'Montserrat' : 'Montserrat',
        'Mauritius' : 'Mauritius',
        'Malawi' : 'Malawi',
        'Malaysia' : 'Malaysia',
        'Namibia' : 'Namibia',
        'New Caledonia' : 'New Caledonia',
        'Niger' : 'Niger',
        'Nigeria' : 'Nigeria',
        'Nicaragua' : 'Nicaragua',
        'Niue' : 'Niue',
        'Netherlands' : 'Netherlands',
        'Norway' : 'Norway',
        'Nepal' : 'Nepal',
        'New Zealand' : 'New Zealand',
        'Oman' : 'Oman',
        'Pakistan' : 'Pakistan',
        'Panama' : 'Panama',
        'Peru' : 'Peru',
        'Philippines' : 'Philippines',
        'Palau' : 'Palau',
        'Papua New Guinea' : 'Papua New Guinea',
        'Poland' : 'Poland',
        'Puerto Rico' : 'Puerto Rico',
        'Dem. Rep. Korea' : 'Dem. Rep. Korea',
        'Portugal' : 'Portugal',
        'Paraguay' : 'Paraguay',
        'Palestine' : 'Palestine',
        'Fr. Polynesia' : 'Fr. Polynesia',
        'Qatar' : 'Qatar',
        'Romania' : 'Romania',
        'Russia' : 'Russia',
        'Rwanda' : 'Rwanda',
        'Saudi Arabia' : 'Saudi Arabia',
        'Sudan' : 'Sudan',
        'S. Sudan' : 'S. Sudan',
        'Senegal' : 'Senegal',
        'Singapore' : 'Singapore',
        'S. Geo. and S. Sandw. Is.' : 'S. Geo. and S. Sandw. Is.',
        'Saint Helena' : 'Saint Helena',
        'Solomon Is.' : 'Solomon Is.',
        'Sierra Leone' : 'Sierra Leone',
        'El Salvador' : 'El Salvador',
        'St. Pierre and Miquelon' : 'St. Pierre and Miquelon',
        'São Tomé and Principe' : 'São Tomé and Principe',
        'Suriname' : 'Suriname',
        'Slovakia' : 'Slovakia',
        'Slovenia' : 'Slovenia',
        'Sweden' : 'Sweden',
        'Swaziland' : 'Swaziland',
        'Seychelles' : 'Seychelles',
        'Syria' : 'Syria',
        'Turks and Caicos Is.' : 'Turks and Caicos Is.',
        'Chad' : 'Chad',
        'Togo' : 'Togo',
        'Thailand' : 'Thailand',
        'Tajikistan' : 'Tajikistan',
        'Turkmenistan' : 'Turkmenistan',
        'Timor-Leste' : 'Timor-Leste',
        'Tonga' : 'Tonga',
        'Trinidad and Tobago' : 'Trinidad and Tobago',
        'Tunisia' : 'Tunisia',
        'Turkey' : 'Turkey',
        'Tanzania' : 'Tanzania',
        'Uganda' : 'Uganda',
        'Ukraine' : 'Ukraine',
        'Uruguay' : 'Uruguay',
        'United States' : 'United States',
        'Uzbekistan' : 'Uzbekistan',
        'St. Vin. and Gren.' : 'St. Vin. and Gren.',
        'Venezuela' : 'Venezuela',
        'U.S. Virgin Is.' : 'U.S. Virgin Is.',
        'Vietnam' : 'Vietnam',
        'Vanuatu' : 'Vanuatu',
        'Samoa' : 'Samoa',
        'Yemen' : 'Yemen',
        'South Africa' : 'South Africa',
        'Zambia' : 'Zambia',
        'Zimbabwe' : 'Zimbabwe',
      }
    }
  };
  
  // 获取浏览器语言
  let currentLangs = navigator.language || navigator.userLanguage;
  let currentLang = (currentLangs === 'zh-CN') ? 'zh-CN' : 'en';
  // --------------------------------------------------------------------------------
  // echarts 流量
  let chartDom1 = document.getElementById('flow');
  let myChart1 = echarts.init(chartDom1, null, {
      renderer: 'canvas',
      useDirtyRect: false
  });
  window.addEventListener('resize', function() {
    myChart1.resize();
  }, { passive: true });
  let option1;

  option1 = {
    title: {
      text: lang[currentLang].flow,
      padding: 10
    },
    grid: {
      bottom: 30
    },
    tooltip: {
      trigger: 'axis'
    },
    legend: {
      icon: 'circle',
      data: [lang[currentLang].download, lang[currentLang].upload],
      emphasis: false,
      top: 10
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: []
    },
    yAxis: {
      name: 'kbit/s',
      nameTextStyle: {
        padding: [0, 0, -5, 0]
      },
    	type: 'value'
    },
    series: [
      {
        name: lang[currentLang].download,
        data: [],
        type: 'line',
        symbolSize: 8,
        showSymbol: false,
        areaStyle: {
          color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
            { offset: 0, color: 'rgba(84, 112, 198, 0.5)' },
            { offset: 1, color: 'rgba(84, 112, 198, 0)' }
          ])
        },
        emphasis: {
          disabled: true
        }
      },
      {
        name: lang[currentLang].upload,
        data: [],
        type: 'line',
        symbolSize: 8,
        showSymbol: false,
        areaStyle: {
          color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
            { offset: 0, color: 'rgba(145, 204, 117, 0.5)' },
            { offset: 1, color: 'rgba(145, 204, 117, 0)' }
          ])
        },
        emphasis: {
          disabled: true
        }
      }
    ]
  };
  option1 && myChart1.setOption(option1);
  // --------------------------------------------------------------------------------
  // echarts 资源
  let chartDom2 = document.getElementById('resource');
  let myChart2 = echarts.init(chartDom2, null, {
      renderer: 'canvas',
      useDirtyRect: false
  });
  window.addEventListener('resize', function() {
    myChart2.resize();
  }, { passive: true });
  let option2;
  
  option2 = {
    title: {
      text: lang[currentLang].resource,
      padding: 10
    },
    grid: {
      bottom: 30
    },
    tooltip: {
      trigger: 'axis'
    },
    legend: {
      icon: 'circle',
      data: ['Cpu', 'Ram'],
      emphasis: false,
      top: 10
    },
    xAxis: {
      type: 'category',
      boundaryGap: false,
      data: []
    },
    yAxis: {
      name: '%',
      nameTextStyle: {
        padding: [0, 0, -5, 0]
      },
    	type: 'value'
    },
    series: [
      {
        name: 'Cpu',
        data: [],
        type: 'line',
        color: 'red',
        symbolSize: 8,
        showSymbol: false,
        areaStyle: {
          color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
            { offset: 0, color: 'rgba(255, 0, 0, 0.5)' },
            { offset: 1, color: 'rgba(255, 0, 0, 0)' }
          ])
        },
        emphasis: {
          disabled: true
        }
      },
      {
        name: 'Ram',
        data: [],
        type: 'line',
        color: 'yellow',
        symbolSize: 8,
        showSymbol: false,
        areaStyle: {
          color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
            { offset: 0, color: 'rgba(255, 255, 0, 0.5)' },
            { offset: 1, color: 'rgba(255, 255, 0, 0)' }
          ])
        },
        emphasis: {
          disabled: true
        }
      }
    ]
  };
  option2 && myChart2.setOption(option2);
  // --------------------------------------------------------------------------------
  // echarts 流量地图
  let chartDom3 = document.getElementById('world');
  let myChart3 = echarts.init(chartDom3, null, {
      renderer: 'canvas',
      useDirtyRect: false
  });
  window.addEventListener('resize', function() {
    myChart3.resize();
  }, { passive: true });
  let option3;

  myChart3.showLoading();
  $.get('./js/world.json', function (geoJson) {
    echarts.registerMap('world', geoJson);
    myChart3.hideLoading();

    option3 = {
      title: {
        text: lang[currentLang].flowMap,
        left: 'center'
      },
      tooltip: {
        trigger: 'item',
        showDelay: 0,
        transitionDuration: 0.2
      },
      visualMap: {
        left: 'right',
        min: 1,
        max: 2,
        inRange: {
          color: ['#e0f3f8', '#abd9e9', '#74add1', '#4575b4', '#313695']
        },
        // text: ['High', 'Low'],
        calculable: true
      },
      series: [
        {
          name: lang[currentLang].flow,
          type: 'map',
          map: 'world',
          // roam: true, // 地图缩放
          emphasis: {
            label: {
              show: true
            }
          },
          data: [],
          // 自定义名称映射
          nameMap: lang[currentLang].countrys,
        }
      ]
    };
  });

  option3 && myChart3.setOption(option3);
  // --------------------------------------------------------------------------------  
  
  // 获取ip数据
  function fetchIpData() {
    fetch('./script.php')
      .then(response => {
        if (!response.ok) {
          throw new Error('The network response is abnormal');
        }
        return response.json();
      })
      .then(data => { 
        if (typeof data !== 'object' || data === null || Object.keys(data).length < 6) {
          throw new Error('The data format is not true');
        }
        let maxNum = [];
        let seriesData = [];
        for (let i = 6; i < Object.keys(data).length; i++) {
          maxNum.push(Object.values(data)[i]);
          seriesData.push({ name: Object.keys(data)[i], value: Object.values(data)[i] }); // ip统计
        }
        option3.series[0].data = seriesData; // 一次性设置数据
        option3.visualMap.max = Math.max.apply(null, maxNum); // 获取最大值
        
        myChart3.setOption(option3); // 更新图表
      })
      .catch(error => {
        console.error('An error occurred while obtaining IP data:', error);
            });
  }
  fetchIpData();
  
  // 运行时间
  let systemTime = document.getElementById('time');
  window.addEventListener('resize', function() {
    systemTime;
  }, { passive: true });

  // 进程
  let systemInfo = document.getElementById('info');
  window.addEventListener('resize', function() {
    systemInfo;
  }, { passive: true });

  function fetchData() {
    fetch('./script.php')
    .then(response => {
      if (!response.ok) {
        throw new Error('The network response is abnormal');
      }
      return response.json();
    })
    .then(data => { 
      if (typeof data !== 'object' || data === null || Object.keys(data).length < 6) {
        throw new Error('The data format is not true');
      }
      let time = new Date().toLocaleTimeString();

      option1.xAxis.data.push(time); // 浏览器时间
      option1.series[0].data.push(data[0]); // 上传
      option1.series[1].data.push(data[1]); // 下载

      option2.xAxis.data.push(time);
      option2.series[0].data.push(data[3]); // ram
      option2.series[1].data.push(data[2]); // cpu
      
      if (option1.xAxis.data.length > 5) {
          option1.xAxis.data.shift();
          option1.series[0].data.shift();
          option1.series[1].data.shift();

          option2.xAxis.data.shift();
          option2.series[0].data.shift();
          option2.series[1].data.shift();
      }
      myChart1.setOption(option1);
      myChart2.setOption(option2);

      // 运行时间
      let runTime = JSON.stringify(data[4]);
      let cleanedRuntime = runTime.match(/\w([^\\]+)/g);
      systemTime.textContent = cleanedRuntime[0]; // 确保安全插入

      // 进程表格
      let processes = JSON.stringify(data[5], null, 2);
      let cleanedData = processes.match(/(\d+)\s+(.*?)\s+(\d+.\d+)\s+(\d+.\d+)/g);
      // 将数据转换为表格格式
      let tableData = cleanedData.map(row => row.match(/(\d+)\s+(.*?)\s+(\d+.\d+)\s+(\d+.\d+)/).slice(1));
      // 创建表格元素
      let table = document.createElement('table');
      
      // 创建表头
      let thead = document.createElement('thead');
      let headerRow = document.createElement('tr');
      
      // 表头内容
      let headers = ['PID', 'CMD', '%CPU', '%MEM'];
      headers.forEach(headerText => {
          let th = document.createElement('th');
          th.textContent = headerText; // 使用 textContent 避免 XSS
          headerRow.appendChild(th);
      });
      thead.appendChild(headerRow);
      table.appendChild(thead);
      
      // 创建表格主体
      let tbody = document.createElement('tbody');

      // 为每一行数据创建表格行
      tableData.forEach(row => {
          let tr = document.createElement('tr');
          
          row.forEach(cellData => {
              let td = document.createElement('td');
              td.textContent = cellData; // 使用 textContent 安全插入数据
              tr.appendChild(td);
          });
          
          tbody.appendChild(tr);
      });

      // 将表格主体添加到表格中
      table.appendChild(tbody);
      
      // 清空并安全地将新表格添加到页面
      systemInfo.textContent = ''; // 清空旧内容
      systemInfo.appendChild(table); // 安全地添加新的表格
    })
    .catch(error => {
      console.error('An error occurred while obtaining data:', error);
      (error.message === 'TypeError: Failed to fetch') ? null : window.location.href = '/login';
    });
  }
  setInterval(fetchData, 3000);
}