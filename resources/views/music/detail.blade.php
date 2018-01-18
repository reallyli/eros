<!DOCTYPE html>
<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="/node_modules/materialize-css/dist/css/materialize.min.css"  media="screen,projection"/>
    <!--Let browser know website is optimized for mobile-->
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
                    <div id="aplayer1" class="aplayer">
                    </div>
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
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="/node_modules/materialize-css/dist/js/materialize.min.js"></script>
    <script type="text/javascript" src="/node_modules/aplayer/dist/APlayer.min.js"></script>
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
    </script>
  </body>
</html>