<!DOCTYPE html>
<html>
  <head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/node_modules/materialize-css/dist/css/materialize.min.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href='/css/detail.css' />
    <link type="text/css" rel="stylesheet" href='/dropload/dist/dropload.css' />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>
  <body>
    <div class="container">
        <div class="col s12 m5">
            <div class="card hoverable">
                <div class="card-image">
                    <img src="{{$musicDetail['songs'][0]['al']['picUrl']}}">
                    <span class="card-title"><b>{{$musicDetail['songs'][0]['name']}}</b></span>
                </div>
                <div id="aplayer1" class="aplayer"></div>
                <div class="card-content">
                    @if($comment['hotComments'])
                        @foreach($comment['hotComments'] as $commentDetail)
                            <div class="divider"></div>
                            <div class="section">
                                <h5>
                                    <div class="chip">
                                        <img src="{{$commentDetail['user']['avatarUrl']}}" alt="{{$commentDetail['user']['nickname']}}">
                                        {{$commentDetail['user']['nickname']}}
                                    </div>
                                    <span class="secondary-content">{{$commentDetail['likedCount']}}<i class="material-icons">star_border</i></span>
                                </h5>
                                <p>
                                    {{$commentDetail['content']}}
                                </p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/node_modules/materialize-css/dist/js/materialize.min.js"></script>
    <script type="text/javascript" src="/node_modules/aplayer/dist/APlayer.min.js"></script>
    <script type="text/javascript" src="/dropload/dist/dropload.min.js"></script>
    {{--<script type="text/javascript" src="/node_modules/axios/dist/axios.min.js"></script>--}}
    <script>
        var author = "{{$musicDetail['songs'][0]['ar'][0]['name']}}";
        var musicName = "{{$musicDetail['songs'][0]['name']}}";
        var toastContent = $('<span>'+musicName+'</span>').add($('<button class="btn-flat toast-action">'+author+'</button>'));
        Materialize.toast(toastContent, 2000, 'rounded');
        var ap = new APlayer({
            element: document.getElementById('aplayer1'),
            autoplay: true,
            showlrc: 1,
            theme: '#bbdefb',
            mode: 'random',
            music: {
                title: musicName,
                author: author,
                url: "{{$source}}",
                lrc: "{{$musicLyric}}"
            }
        });
        ap.play();
        console.log(ap.lrc);

        function getQueryString(name) {
            var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
            var r = window.location.search.substr(1).match(reg);
            if (r != null) {
                return unescape(r[2]);
            }
            return null;
        }
        // my dropload
//        var requestUrl = 'https://'+window.location.host+'/music/comment?id='+getQueryString('id');
        var requestUrl = 'https://api.hixiaogan.cn/music/comment?id='+getQueryString('id');
        $('.card-content').dropload({
            scrollArea : window,
            loadDownFn : function(me){
                $.ajax({
                    type: 'GET',
                    url: requestUrl,
                    dataType: 'json',
                    success: function(data){
                        alert(data);
                        // 每次数据加载完，必须重置
                        me.resetload();
                    },
                    error: function(xhr, type){
                        alert('Ajax error!');
                        // 即使加载出错，也得重置
                        me.resetload();
                    }
                });
            }
        });
    </script>
  </body>
</html>