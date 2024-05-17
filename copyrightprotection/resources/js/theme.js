import { docReady } from './utils';
import handleNavbarVerticalCollapsed from './navbar-vertical';
// import detectorInit from './detector';
import tooltipInit from './tooltip';
import popoverInit from './popover';
import navbarTopDropShadow from './navbar-top';
import toastInit from './toast';
import progressAnimationToggle from './progress';
import glightboxInit from './glightbox';
import plyrInit from './plyr';
import initMap from './googleMap';
import dropzoneInit from './dropzone';
import choicesInit from './choices';
import barChartInit from './charts/chartjs/chart-bar';
import formValidationInit from './form-validation';
import leafletActiveUserInit from './leaflet-active-user';
import countupInit from './countup';
import copyLink from './copy-link';
import typedTextInit from './typed';
import navbarDarkenOnScroll from './navbar-darken-on-scroll';
import tinymceInit from './tinymce';
import bulkSelectInit from './bulk-select';
import quantityInit from './quantity';
import navbarComboInit from './navbar-combo';
import listInit from './list';
import chatInit from './chat';
import draggableInit from './draggable';
import kanbanInit from './kanban';
import { fullCalendarInit } from './fullcalendar';
import appCalendarInit from './calendar/index';
import managementCalendarInit from './calendar/management-calendar';
import lottieInit from './lottie';
import wizardInit from './wizard';
import swiperInit from './swiper';
import ratingInit from './rater';
import searchInit from './search';
import cookieNoticeInit from './cookie-notice';
import themeControl from './theme-control';
import dropdownOnHover from './dropdown-on-hover';
import productShareDoughnutInit from './charts/chartjs/product-share-doughnut';
import chartHalfDoughnutInit from './charts/chartjs/chart-half-doughnut';
import totalSalesEcommerce from './charts/echarts/total-sales-ecommerce';
import avgEnrollmentRateInit from './charts/echarts/average-enrollment-rate';
import salesByPosLocationInit from './charts/echarts/sales-by-pos-location';
import returningCustomerRateInit from './charts/echarts/returning-customer-rate';
import topProductsInit from './charts/echarts/top-products';
import courseEnrollmentsInit from './charts/echarts/course-enrollments';
import marketShareEcommerceInit from './charts/echarts/market-share-ecommerce';
import totalSalesInit from './charts/echarts/total-sales';
import weeklySalesInit from './charts/echarts/weekly-sales';
import marketShareInit from './charts/echarts/market-share';
import weeklyGoalsInit from './charts/echarts/weekly-goals-lms';
import totalOrderInit from './charts/echarts/total-order';
import candleChartInit from './charts/echarts/candle-chart';
import grossRevenueChartInit from './charts/echarts/gross-revenue';
import browsedCoursesInit from './charts/echarts/browsed-courses';
import courseStatusInit from './charts/echarts/course-status';
import assignmentScoresInit from './charts/echarts/assignment-scores';
import scrollbarInit from './scrollbar';
import iconCopiedInit from './icons';
import reportForThisWeekInit from './charts/echarts/report-for-this-week';
import basicEchartsInit from './charts/echarts/basic-echarts';
import trendingKeywordsInit from './charts/d3/trending-keywords';
import D3PackedBubbleInit from './charts/d3/d3-packed-bubble';
import chartScatter from './charts/chartjs/chart-scatter';
import chartDoughnut from './charts/chartjs/chart-doughnut';
import chartPie from './charts/chartjs/chart-pie';
import chartPolar from './charts/chartjs/chart-polar';
import chartRadar from './charts/chartjs/chart-radar';
import chartCombo from './charts/chartjs/chart-combo';
import chartBubble from './charts/chartjs/chart-bubble';
import dropdownMenuInit from './dropdown-menu';
import audienceChartInit from './charts/echarts/audience';
import sessionByBrowserChartInit from './charts/echarts/session-by-browser';
import sessionByCountryChartInit from './charts/echarts/session-by-country';
import activeUsersChartReportInit from './charts/echarts/active-users-report';
import trafficChannelChartInit from './charts/echarts/traffic-channels';
import bounceRateChartInit from './charts/echarts/bounce-rate';
import usersByTimeChartInit from './charts/echarts/users-by-time';
import sessionByCountryMapInit from './charts/echarts/session-by-country-map';
import mostLeadsInit from './charts/echarts/most-leads';
import closedVsGoalInit from './charts/echarts/closed-vs-goal';
import leadConversionInit from './charts/echarts/lead-conversion';
import dealStorageFunnelInit from './charts/echarts/deal-storage-funnel';
import revenueChartInit from './charts/echarts/crm-revenue';
import locationBySessionInit from './charts/echarts/location-by-session-crm';
import realTimeUsersChartInit from './charts/echarts/real-time-users';
import linePaymentChartInit from './charts/echarts/line-payment';
import chartLine from './charts/chartjs/chart-line';
import bandwidthSavedInit from './charts/echarts/bandwidth-saved';
import marketingExpensesInit from './charts/echarts/marketing-expenses';
import treeviewInit from './treeview';
import bottomBarInit from './bottom-bar';
import scrollInit from './scroll';
import echartsUnresolvedTicketsInit from './charts/echarts/unresolved-tickets';
import echartsNumberOfTicketsInit from './charts/echarts/number-of-tickets';
import echartsCustomerSatisfactionInit from './charts/echarts/customer-satisfaction';
import echartsDistributionOfPerformanceInit from './charts/echarts/distribution-of-performance';
import echartsSatisfactionSurveyInit from './charts/echarts/satisfaction-survey';
import echartsReceivedTicketsInit from './charts/echarts/received-tickets';
import topCustomersChartInit from './charts/echarts/top-customers';
import ticketVolumeChartInit from './charts/echarts/ticket-volume';
import echartTicketPriority from './charts/echarts/ticket-priority';
import hideOnCollapseInit from './hide-on-collapse';
import userByLocationInit from './charts/echarts/user-by-location-lms';
import dataTablesInit from './datatables';
import select2Init from './select2';
import inputmaskInit from './inputmask';
import unresolvedTicketsTabInit from './unresolved-tickets-tab';
import picmoInit from './picmo';
import nouisliderInit from './nouislider';
import advanceAjaxTableInit from './advance-ajax-table';

/* -------------------------------------------------------------------------- */
/*                            Theme Initialization                            */
/* -------------------------------------------------------------------------- */
// docReady(detectorInit);
docReady(handleNavbarVerticalCollapsed);
docReady(totalOrderInit);
docReady(weeklySalesInit);
docReady(marketShareInit);
docReady(totalSalesInit);
docReady(topProductsInit);
docReady(navbarTopDropShadow);
docReady(tooltipInit);
docReady(popoverInit);
docReady(toastInit);
docReady(progressAnimationToggle);
docReady(glightboxInit);
docReady(plyrInit);
docReady(initMap);
docReady(dropzoneInit);
docReady(choicesInit);
docReady(formValidationInit);
docReady(barChartInit);
docReady(leafletActiveUserInit);
docReady(countupInit);
docReady(copyLink);
docReady(navbarDarkenOnScroll);
docReady(typedTextInit);
docReady(tinymceInit);
docReady(chatInit);
docReady(quantityInit);
docReady(navbarComboInit);
docReady(swiperInit);
docReady(ratingInit);
docReady(draggableInit);
docReady(kanbanInit);
docReady(fullCalendarInit);
docReady(appCalendarInit);
docReady(managementCalendarInit);
docReady(lottieInit);
docReady(wizardInit);
docReady(searchInit);
docReady(cookieNoticeInit);
docReady(themeControl);
docReady(dropdownOnHover);
docReady(marketShareEcommerceInit);
docReady(productShareDoughnutInit);
docReady(totalSalesEcommerce);
docReady(avgEnrollmentRateInit);
docReady(bandwidthSavedInit);
docReady(salesByPosLocationInit);
docReady(returningCustomerRateInit);
docReady(candleChartInit);
docReady(grossRevenueChartInit);
docReady(scrollbarInit);
docReady(iconCopiedInit);
docReady(reportForThisWeekInit);
docReady(basicEchartsInit);
docReady(chartScatter);
docReady(chartDoughnut);
docReady(chartPie);
docReady(chartPolar);
docReady(chartRadar);
docReady(chartCombo);
docReady(dropdownMenuInit);
docReady(audienceChartInit);
docReady(sessionByBrowserChartInit);
docReady(sessionByCountryChartInit);
docReady(activeUsersChartReportInit);
docReady(trafficChannelChartInit);
docReady(bounceRateChartInit);
docReady(usersByTimeChartInit);
docReady(sessionByCountryMapInit);
docReady(mostLeadsInit);
docReady(closedVsGoalInit);
docReady(leadConversionInit);
docReady(dealStorageFunnelInit);
docReady(revenueChartInit);
docReady(locationBySessionInit);
docReady(realTimeUsersChartInit);
docReady(linePaymentChartInit);
docReady(chartBubble);
docReady(chartLine);
docReady(treeviewInit);
docReady(scrollInit);
docReady(echartsUnresolvedTicketsInit);
docReady(echartsNumberOfTicketsInit);
docReady(echartsCustomerSatisfactionInit);
docReady(echartsDistributionOfPerformanceInit);
docReady(echartsSatisfactionSurveyInit);
docReady(echartsReceivedTicketsInit);
docReady(topCustomersChartInit);
docReady(ticketVolumeChartInit);
docReady(echartTicketPriority);
docReady(userByLocationInit);
docReady(courseEnrollmentsInit);
docReady(weeklyGoalsInit);
docReady(assignmentScoresInit);
docReady(browsedCoursesInit);
docReady(courseStatusInit);
docReady(bottomBarInit);
docReady(marketingExpensesInit);
docReady(chartHalfDoughnutInit);
docReady(trendingKeywordsInit);
docReady(D3PackedBubbleInit);
docReady(dataTablesInit);
docReady(select2Init);
docReady(hideOnCollapseInit);
docReady(unresolvedTicketsTabInit);
docReady(inputmaskInit);
docReady(picmoInit);
docReady(nouisliderInit);
docReady(bulkSelectInit);
docReady(advanceAjaxTableInit);
docReady(listInit);
