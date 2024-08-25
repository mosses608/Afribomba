@if(session()->has('success_flash_msg'))
<div class="positive-flash-message">
    <p>{{session('success_flash_msg')}}</p>
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