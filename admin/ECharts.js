// https://echarts.apache.org/examples/zh/editor.html?c=map-usa
import * as echarts from 'echarts';

var ROOT_PATH = 'https://echarts.apache.org/examples';

var chartDom = document.getElementById('main');
var myChart = echarts.init(chartDom);
var option;

myChart.showLoading();
$.get(ROOT_PATH + '/data/asset/geo/world.json', function (geoJosn) {
  echarts.registerMap('world', geoJosn);
  myChart.hideLoading();

  option = {
    title: {
      text: 'world',
      left: 'center'
    },
    tooltip: {
      trigger: 'item',
      showDelay: 0,
      transitionDuration: 0.2
    },
    visualMap: {
      left: 'right',
      min: 0,
      max: 38000000,
      inRange: {
        color: ['#e0f3f8', '#abd9e9', '#74add1', '#4575b4', '#313695']
      },
      text: ['High', 'Low'],
      calculable: true
    },
    series: [
      {
        name: 'world',
        type: 'map',
        map: 'world',
        // roam: true,
        emphasis: {
          label: {
            show: true
          }
        },
        data: [
          { name: 'Canada', value: 4822023 },
          { name: 'China', value: 425111 },
          { name: 'Japan', value: 11055448 },
          { name: 'Russia', value: 38055448 },
          { name: 'Australia', value: 22589448 }
        ]
      }
    ]
  };
  myChart.setOption(option);
});

option && myChart.setOption(option);

https://echarts.apache.org/examples/zh/editor.html?c=area-basic&version=5.5.1
import * as echarts from 'echarts';

var chartDom = document.getElementById('main');
var myChart = echarts.init(chartDom);
var option;

option = {
  title: {
    text: '流量',
    left: 10,
    top: 10
  },
  tooltip: {
    trigger: 'axis'
  },
  legend: {
    icon: 'circle',
    data: ['下载', '上传'],
    emphasis: false,
    top: 10
  },
  grid: {
    top: 150,
    left: 50,
    right: 50,
    bottom: 50
  },
  xAxis: {
    type: 'category',
    boundaryGap: false,
    data: ['A', 'B', 'C', 'D', 'E']
  },
  yAxis: {},
  series: [
    {
      name: '下载',
      data: [10, 22, 28, 23, 19],
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
      name: '上传',
      data: [25, 14, 23, 35, 10],
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

option && myChart.setOption(option);




// 实时数据
import * as echarts from 'echarts';

var chartDom = document.getElementById('main');
var myChart = echarts.init(chartDom);
var option;

const categories = (function () {
  let now = new Date();
  let res = [];
  let len = 10;
  while (len--) {
    res.unshift(now.toLocaleTimeString().replace(/^\D*/, ''));
    now = new Date(+now - 2000);
  }
  return res;
})();

const data = (function () {
  let res = [];
  let len = 10;
  while (len--) {
    res.push(Math.round(Math.random() * 1000));
  }
  return res;
})();

const data2 = (function () {
  let res = [];
  let len = 0;
  while (len < 10) {
    res.push(+(Math.random() * 10 + 5).toFixed(1));
    len++;
  }
  return res;
})();

option = {
  title: {
    text: '流量',
    left: 10,
    top: 10
  },
  tooltip: {
    trigger: 'axis'
  },
  legend: {
    icon: 'circle',
    data: ['下载', '上传'],
    emphasis: false,
    top: 10
  },
  xAxis: {
    type: 'category',
    boundaryGap: false,
    data: categories
  },
  yAxis: {},
  series: [
    {
      name: '下载',
      data: data,
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
      name: '上传',
      data: data2,
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

setInterval(function () {
  let axisData = new Date().toLocaleTimeString().replace(/^\D*/, '');
  data.shift();
  data.push(Math.round(Math.random() * 1000));
  data2.shift();
  data2.push(+(Math.random() * 10 + 5).toFixed(1));
  categories.shift();
  categories.push(axisData);
  myChart.setOption({
    xAxis: [
      {
        data: categories
      }
    ],
    series: [
      {
        data: data
      },
      {
        data: data2
      }
    ]
  });
}, 1000);

option && myChart.setOption(option);