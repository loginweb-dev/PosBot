@extends('layouts.app')
@section('css')
<style type="text/css">
	@page {
	    size: A4 landscape;
	    max-height:100%; 
	    max-width:100%;
	    margin-top: 1.2cm;
	    margin-bottom: 1.2cm;
	    margin-left: 1cm;
	    margin-right: 1cm;
	    size: 210mm 287mm;
	}
}
</style>
@endsection
@section('content')
<div class="container-fluid">
	<h5>
		<i class="fab fa-wpforms"></i>
		@lang('messages.reports_for_form', ['form_name' => $form->name])
	</h5>
    <div class="row justify-content-center">
    	<div class="col-md-12">
    		<div class="card card-outline card-info">
        		<div class="card-header">
        			<i class="fas fa-chart-line"></i>
        			@lang('messages.analytics')
        		</div>
		        <div class="card-body">
		        	<div class="row mb-5">
		        		<div class="col-md-12">
		        			<div id="visitors_chart"></div>
		        		</div>
		        	</div>
		        	<div class="row">
			        	<div class="col-md-12">
		        			<div id="referrers_chart"></div>
		        		</div>
		        	</div>
		        </div>
		    </div>
    	</div>
        <div class="col-md-12">
    		<div class="card card-outline card-info">
        		<div class="card-header">
        			<i class="fas fa-chart-pie"></i>
        			@lang('messages.reports')
        		</div>
		        <div class="card-body">
		        	<div class="row">
		        		@forelse($charts as $key => $chart)
			        		<div class="col-md-6 mb-5">
			        			<div id="{{$key}}" style="min-width: 330px;height: 400px;max-width: 500px;margin: 0 auto"></div>
			        		</div>
			        	@empty
			        		<div class="col-md-12">
				        		<div class="alert alert-info" role="alert">
								  @lang('messages.no_report_found')
								</div>
							</div>
		        		@endforelse
		        	</div>
		        </div>
		    </div>
		</div>
	</div>
</div>

@endsection
@section('footer')
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		@if(!empty($charts))
			@foreach($charts as $key => $chart)
				@php
					$chart_data = [];
					foreach($chart['values'] as $label => $data)  {
						$chart_data[]= ['name' => $label, 'y' => $data];
					}
				@endphp

				//initializing highchart.js
				var high_chart_data = {!!json_encode($chart_data)!!};
				
				Highcharts.chart('{{$key}}', {
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: true,
						type: 'pie',
						events: {
					      beforePrint: function() {
					        this.orgChartWidth = this.chartWidth;
					        this.orgChartHeight = this.chartHeight;
					        this.setSize(8500, this.orgChartHeight);
					      },
					      afterPrint: function() {
					        this.setSize(this.orgChartWidth, this.orgChartHeight);
					      }
					    }
					},
					title: {
						text: '{{$chart["name"]}}'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>  <i>({point.y})</i>'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.percentage:.1f} %  <i>({point.y})</i>'
							}
						}
					},
					series: [
						{
							name: '{{$chart["name"]}}',
							colorByPoint: true,
							data: high_chart_data
						}
					]
				});
			@endforeach
		@endif

		//vistors chart
		Highcharts.chart('visitors_chart', {
		    xAxis: {
		        categories: {!!json_encode($visitors_chart['labels'])!!}
		    },
		    chart: {
		        type: 'line',
		        events: {
			      beforePrint: function() {
			        this.orgChartWidth = this.chartWidth;
			        this.orgChartHeight = this.chartHeight;
			        this.setSize(8500, this.orgChartHeight);
			      },
			      afterPrint: function() {
			        this.setSize(this.orgChartWidth, this.orgChartHeight);
			      }
			    }
		    },
		    title: {
		        text: "{{$visitors_chart['title']}}"
		    },
		    series: [{
		    	name: "{{$visitors_chart['total_visits_label']}}",
		        data: {!!json_encode($visitors_chart['total_visits'])!!}
		    }, {
		    	name: "{{$visitors_chart['unique_visits_label']}}",
		        data: {!!json_encode($visitors_chart['unique_visits'])!!}
		    }]
		});

		//referrers chart
		Highcharts.chart('referrers_chart', {
			chart: {
				type: 'pie',
				events: {
			      beforePrint: function() {
			        this.orgChartWidth = this.chartWidth;
			        this.orgChartHeight = this.chartHeight;
			        this.setSize(8500, this.orgChartHeight);
			      },
			      afterPrint: function() {
			        this.setSize(this.orgChartWidth, this.orgChartHeight);
			      }
			    }
			},
			title: {
				text: "{{$referrers_chart['name']}}"
			},
			tooltip: {
				pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>  <i>({point.y})</i>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						format: '<b>{point.name}</b>: {point.percentage:.1f} %  <i>({point.y})</i>'
					}
				}
			},
			series: [
				{
					name: "{{$referrers_chart['name']}}",
					colorByPoint: true,
					data: {!!json_encode($referrers_chart['values'])!!}
				}
			]
		});
	});
</script>
@endsection