<x-app-layout>
    <div class="row g-3 mb-3">
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100 ecommerce-card-min-width">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2 d-flex align-items-center">Total Projects</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row">
                        <div class="col">
                            <p class="font-sans-serif lh-1 mb-1 fs-4">{{$projectCount}}</p>
                        </div>
                        <div class="col-auto ps-0">
                            <div class="echart-bar-weekly-sales h-100" style="user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;" _echarts_instance_="ec_1693158397676"><div style="position: relative; width: 136px; height: 61px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas data-zr-dom-id="zr_0" width="136" height="61" style="position: absolute; left: 0px; top: 0px; width: 136px; height: 61px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div class="" style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; background-color: rgb(249, 250, 253); border-width: 1px; border-radius: 4px; color: rgb(11, 23, 39); font: 14px / 21px sans-serif; padding: 7px 10px; top: 0px; left: 0px; transform: translate3d(65px, -26px, 0px); border-color: rgb(216, 226, 239); pointer-events: none; visibility: hidden; opacity: 0;">Fri : 110</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xxl-3">
            <div class="card h-md-100">
                <div class="card-header pb-0">
                    <h6 class="mb-0 mt-2">Total Reports</h6>
                </div>
                <div class="card-body d-flex flex-column justify-content-end">
                    <div class="row justify-content-between">
                        <div class="col-auto align-self-end">
                            <div class="fs-4 fw-normal font-sans-serif text-700 lh-1 mb-1">{{$reportsCount}}</div>
                        </div>
                        <div class="col-auto ps-0 mt-n4">
                            <div class="echart-default-total-order" data-echarts="{&quot;tooltip&quot;:{&quot;trigger&quot;:&quot;axis&quot;,&quot;formatter&quot;:&quot;{b0} : {c0}&quot;},&quot;xAxis&quot;:{&quot;data&quot;:[&quot;Week 4&quot;,&quot;Week 5&quot;,&quot;Week 6&quot;,&quot;Week 7&quot;]},&quot;series&quot;:[{&quot;type&quot;:&quot;line&quot;,&quot;data&quot;:[20,40,100,120],&quot;smooth&quot;:true,&quot;lineStyle&quot;:{&quot;width&quot;:3}}],&quot;grid&quot;:{&quot;bottom&quot;:&quot;2%&quot;,&quot;top&quot;:&quot;2%&quot;,&quot;right&quot;:&quot;10px&quot;,&quot;left&quot;:&quot;10px&quot;}}" data-echart-responsive="true" style="user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); position: relative;" _echarts_instance_="ec_1693158397681"><div style="position: relative; width: 138px; height: 90px; padding: 0px; margin: 0px; border-width: 0px; cursor: default;"><canvas data-zr-dom-id="zr_0" width="138" height="90" style="position: absolute; left: 0px; top: 0px; width: 138px; height: 90px; user-select: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); padding: 0px; margin: 0px; border-width: 0px;"></canvas></div><div class="" style="position: absolute; display: block; border-style: solid; white-space: nowrap; z-index: 9999999; box-shadow: rgba(0, 0, 0, 0.2) 1px 2px 10px; background-color: rgb(249, 250, 253); border-width: 1px; border-radius: 4px; color: rgb(11, 23, 39); font: 14px / 21px sans-serif; padding: 7px 10px; top: 0px; left: 0px; transform: translate3d(56px, 39px, 0px); border-color: rgb(216, 226, 239); pointer-events: none; visibility: hidden; opacity: 0;">Week 6 : 100</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
