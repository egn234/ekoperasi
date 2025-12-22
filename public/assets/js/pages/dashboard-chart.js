// Generic Dashboard Chart Management
class DashboardChart {
  constructor(rolePath = 'admin') {
    this.currentChart = null;
    this.baseUrl = '';
    this.rolePath = rolePath;
    this.init();
  }

  init() {
    // Set base URL from global variable or current location
    this.baseUrl = window.baseUrl || window.location.origin;

    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.initializeChart());
    } else {
      this.initializeChart();
    }
  }

  initializeChart() {
    // Check if required elements exist
    const chartContainer = document.getElementById('spline_area');
    const chartTypeEl = document.getElementById('chartType');
    const chartRangeEl = document.getElementById('chartRange');

    if (!chartContainer || !chartTypeEl || !chartRangeEl) {
      console.log('Required chart elements not found');
      return;
    }

    // Small delay to ensure all elements are rendered
    setTimeout(() => {
      this.loadChartData();
      this.setupEventListeners();
    }, 100);
  }

  setupEventListeners() {
    const chartTypeEl = document.getElementById('chartType');
    const chartRangeEl = document.getElementById('chartRange');
    const startDateEl = document.getElementById('startDate');
    const endDateEl = document.getElementById('endDate');
    const refreshChartEl = document.getElementById('refreshChart');
    const customDateRangeEl = document.getElementById('customDateRange');

    if (chartTypeEl) {
      chartTypeEl.addEventListener('change', () => this.loadChartData());
    }

    if (chartRangeEl) {
      chartRangeEl.addEventListener('change', () => {
        if (customDateRangeEl) {
          if (chartRangeEl.value === 'custom') {
            customDateRangeEl.style.display = 'block';
          } else {
            customDateRangeEl.style.display = 'none';
            this.loadChartData();
          }
        }
      });
    }

    if (startDateEl) {
      startDateEl.addEventListener('change', () => this.loadChartData());
    }

    if (endDateEl) {
      endDateEl.addEventListener('change', () => this.loadChartData());
    }

    if (refreshChartEl) {
      refreshChartEl.addEventListener('click', (e) => {
        e.preventDefault();
        this.loadChartData();
      });
    }
  }

  loadChartData() {
    const chartTypeEl = document.getElementById('chartType');
    const chartRangeEl = document.getElementById('chartRange');
    const startDateEl = document.getElementById('startDate');
    const endDateEl = document.getElementById('endDate');
    const chartLoadingEl = document.getElementById('chartLoading');
    const splineAreaEl = document.getElementById('spline_area');

    if (!chartTypeEl || !chartRangeEl || !chartLoadingEl || !splineAreaEl) {
      console.log('Required chart elements not found');
      return;
    }

    const chartType = chartTypeEl.value;
    const chartRange = chartRangeEl.value;
    const startDate = startDateEl ? startDateEl.value : '';
    const endDate = endDateEl ? endDateEl.value : '';

    // Show loading
    chartLoadingEl.style.display = 'block';
    splineAreaEl.style.display = 'none';

    let url = `${this.baseUrl}/${this.rolePath}/dashboard/getChartData?type=${chartType}&range=${chartRange}`;

    if (chartRange === 'custom' && startDate && endDate) {
      url += `&start_date=${startDate}&end_date=${endDate}`;
    }

    fetch(url)
      .then(response => response.json())
      .then(data => {
        this.hideLoading();
        if (data.status === 'success') {
          // Only update chart info if data has info (admin doesn't use this)
          if (data.info) {
            this.updateChartInfo(data.info);
          }
          this.renderChart(data.data, chartType);
        } else {
          this.showError(data.message);
        }
      })
      .catch(error => {
        this.hideLoading();
        console.error('Error loading chart data:', error);
        this.showError('Gagal memuat data chart');
      });
  }

  updateChartInfo(info) {
    // Info updating is handled by the dashboard's existing cards
    // This method kept for compatibility but admin doesn't use it
  }

  updateChartStats(dataArray) {
    // Calculate stats from chart data
    if (!dataArray || dataArray.length === 0) return;

    const totalDataPoints = dataArray.length;
    const highestValue = Math.max(...dataArray);
    const averageValue = Math.round(dataArray.reduce((sum, val) => sum + val, 0) / dataArray.length);

    // Indonesian number formatting function
    const formatIndonesian = (num) => {
      const isNegative = num < 0;
      const absNum = Math.abs(num);
      let formatted = '';

      if (absNum >= 1000000000) {
        formatted = 'Rp ' + (absNum / 1000000000).toFixed(1).replace('.', ',') + ' Miliar';
      } else if (absNum >= 1000000) {
        formatted = 'Rp ' + (absNum / 1000000).toFixed(1).replace('.', ',') + ' Juta';
      } else if (absNum >= 1000) {
        formatted = 'Rp ' + (absNum / 1000).toFixed(1).replace('.', ',') + ' Ribu';
      } else {
        formatted = 'Rp ' + absNum.toLocaleString('id-ID');
      }

      return isNegative ? '-' + formatted : formatted;
    };

    // Update total data points
    const totalDataPointsEl = document.getElementById('totalDataPoints');
    if (totalDataPointsEl) {
      totalDataPointsEl.textContent = totalDataPoints.toLocaleString('id-ID');
    }

    // Update highest value
    const highestValueEl = document.getElementById('highestValue');
    if (highestValueEl) {
      highestValueEl.textContent = formatIndonesian(highestValue);
    }

    // Update average value
    const averageValueEl = document.getElementById('averageValue');
    if (averageValueEl) {
      averageValueEl.textContent = formatIndonesian(averageValue);
    }
  }

  renderChart(data, chartType) {
    const colors = this.getChartColorsArray('#spline_area');
    if (!colors || colors.length === 0) {
      console.log('Chart colors not found');
      return;
    }

    let series = [];
    let categories = [];

    // Extract categories (dates/months)
    if (data.length > 0) {
      categories = data.map(item => item.month_name || item.month || item.period);
    }

    // Build series based on chart type - simplified to single series
    if (chartType === 'deposit') {
      series = [{
        name: 'Total Simpanan',
        data: data.map(item => parseInt(item.saldo || item.amount || 0))
      }];
    } else if (chartType === 'loan') {
      series = [{
        name: 'Total Pinjaman',
        data: data.map(item => parseInt(item.saldo || item.amount || 0))
      }];
    } else if (chartType === 'member') {
      series = [{
        name: 'Jumlah Anggota',
        data: data.map(item => parseInt(item.count || 0))
      }];
    }

    // Check if we have negative values for gradient color
    const hasNegativeValues = series[0] && series[0].data && series[0].data.some(value => value < 0);
    const chartColors = hasNegativeValues ? ['#ef4444', '#dc2626'] : colors;

    console.log('Chart data:', series[0]?.data);
    console.log('Has negative values:', hasNegativeValues);
    console.log('Using colors:', chartColors);

    const options = {
      series: series,
      chart: {
        height: 350,
        type: 'area',
        foreColor: '#9ba7b2',
        toolbar: {
          show: false
        }
      },
      colors: chartColors,
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2
      },
      fill: {
        type: 'gradient',
        gradient: {
          shadeIntensity: 1,
          opacityFrom: hasNegativeValues ? 0.4 : 0.3,
          opacityTo: hasNegativeValues ? 0.8 : 0.9,
          stops: [0, 90, 100],
          colorStops: hasNegativeValues ? [
            {
              offset: 0,
              color: '#ef4444',
              opacity: 0.4
            },
            {
              offset: 100,
              color: '#dc2626',
              opacity: 0.8
            }
          ] : []
        }
      },
      xaxis: {
        categories: categories,
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        labels: {
          formatter: function (val) {
            if (chartType === 'member') {
              return val.toLocaleString();
            }
            return val > 1000000 ? (val / 1000000).toFixed(1) + 'M' : val.toLocaleString();
          }
        }
      },
      grid: {
        borderColor: '#40475D'
      },
      tooltip: {
        x: {
          format: 'dd/MM/yy HH:mm'
        },
        y: {
          formatter: function (val) {
            if (val >= 1000000000) {
              return 'Rp ' + (val / 1000000000).toFixed(1).replace('.', ',') + ' Miliar';
            } else if (val >= 1000000) {
              return 'Rp ' + (val / 1000000).toFixed(1).replace('.', ',') + ' Juta';
            } else if (val >= 1000) {
              return 'Rp ' + (val / 1000).toFixed(1).replace('.', ',') + ' Ribu';
            } else {
              return 'Rp ' + val.toLocaleString('id-ID');
            }
          }
        }
      }
    };

    // Show chart container BEFORE rendering so ApexCharts can calculate dimensions
    const chartEl = document.querySelector('#spline_area');
    chartEl.style.display = 'block';

    // Create new chart
    this.currentChart = new ApexCharts(chartEl, options);
    this.currentChart.render();

    // Update chart info
    this.updateChartStats(series[0].data);

    // Trigger a resize event to ensure correct width calculation
    setTimeout(() => {
      window.dispatchEvent(new Event('resize'));
    }, 100);
  }

  getChartColorsArray(elementId) {
    const element = document.querySelector(elementId);
    if (!element) {
      console.log('Chart element not found:', elementId);
      return [];
    }

    const colors = element.getAttribute('data-colors');
    if (!colors) {
      console.log('Chart colors attribute not found');
      return [];
    }

    try {
      const colorArray = JSON.parse(colors);
      return colorArray.map(function (value) {
        var newValue = value.replace(" ", "");
        if (newValue.indexOf(",") === -1) {
          var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
          return color || newValue;
        } else {
          var val = value.split(',');
          if (val.length == 2) {
            var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
            rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
            return rgbaColor;
          } else {
            return newValue;
          }
        }
      });
    } catch (error) {
      console.error('Error parsing chart colors:', error, 'Colors data:', colors);
      return ['#038edc', '#51d28c', '#f7cc53'];
    }
  }

  hideLoading() {
    const chartLoadingEl = document.getElementById('chartLoading');
    if (chartLoadingEl) {
      chartLoadingEl.style.display = 'none';
    }
  }

  showError(message) {
    console.error('Chart error:', message);
    const splineAreaEl = document.getElementById('spline_area');
    if (splineAreaEl) {
      splineAreaEl.innerHTML = `<div class="text-center p-4"><p class="text-muted">${message}</p></div>`;
      splineAreaEl.style.display = 'block';
    }
  }
}

// Auto-initialize for backward compatibility
document.addEventListener('DOMContentLoaded', function () {
  // Try to detect role from URL
  const path = window.location.pathname;
  let role = 'admin'; // default

  if (path.includes('/ketua/')) {
    role = 'ketua';
  } else if (path.includes('/bendahara/')) {
    role = 'bendahara';
  } else if (path.includes('/anggota/')) {
    role = 'anggota';
  }

  // Initialize chart with detected role
  window.dashboardChart = new DashboardChart(role);
});