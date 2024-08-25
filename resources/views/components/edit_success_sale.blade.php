@if(session()->has('edit_success_sale'))
<div class="positive-flash-message">
    <p>{{session('edit_success_sale')}}</p>
    <button class="close-flash-msg" onclick="closeFlash()">&times;</button><br>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function(){
        var flashMessage = document.querySelector('.positive-flash-message');
                setTimeout(() => {
                    flashMessage.style.display='none';
                }, 5000);
            });
</script>



