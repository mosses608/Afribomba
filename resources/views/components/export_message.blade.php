@if(session()->has('export_message'))
<div class="positive-flash-message">
    <p>{{session('export_message')}}</p>
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