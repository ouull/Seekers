// Weekly Activity Chart
const ctx = document.getElementById('weeklyActivityChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
    datasets: [
      {
        label: 'Found Item',
        data: [20, 30, 70, 90, 60, 80, 50],
        backgroundColor: '#8c1515',
      },
      {
        label: 'Lost Report',
        data: [15, 40, 50, 75, 55, 65, 45],
        backgroundColor: '#cccccc',
      },
    ],
  },
});

// Statistics Pie Chart
const statsCtx = document.getElementById('statisticsChart').getContext('2d');
new Chart(statsCtx, {
  type: 'pie',
  data: {
    labels: ['Catalog', 'Loss Report', 'Rejected Item', 'Claim Approval'],
    datasets: [
      {
        data: [30, 15, 20, 35],
        backgroundColor: ['#8c1515', '#a83737', '#d94f4f', '#f36e6e'],
      },
    ],
  },
});


// Claims Pie Chart
const claimsCanvas = document.getElementById('claimsChart');
const approvedCount = parseInt(claimsCanvas.getAttribute('data-approved')) || 0;
const rejectedCount = parseInt(claimsCanvas.getAttribute('data-rejected')) || 0;

const claimsChart = new Chart(claimsCanvas.getContext('2d'), {
    type: 'pie',
    data: {
        labels: ['Approved', 'Rejected'],
        datasets: [{
            data: [approvedCount, rejectedCount],
            backgroundColor: ['#4CAF50', '#FF5252'],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
