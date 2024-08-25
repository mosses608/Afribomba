@if(session()->has('user_pass'))
<div class="positive-flash-message">
    <p>{{session('user_pass')}}</p>
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