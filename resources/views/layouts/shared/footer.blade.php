@php
    \Session::forget('success');
@endphp
<!-- Footer Start -->
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                &copy;  <script>document.write(new Date().getFullYear())</script>  <?=  ucfirst($client_head ? $client_head->company_name : '') ?? 'Royo' ?>. All Right reserved
            </div>
            <!-- <div class="col-md-6">
                <div class="text-md-right footer-links d-none d-sm-block">
                    <a href="javascript:void(0);">About Us</a>
                    <a href="javascript:void(0);">Help</a>
                    <a href="javascript:void(0);">Contact Us</a>
                </div>
            </div> -->
        </div>
    </div>
</footer>
<!-- end Footer -->