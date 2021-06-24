<!doctype html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Myphoto</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>  
@media screen and (min-width:800px) { 
  .thumbnail:hover{transform: scale(1.2) ;
      z-index:999; 
  }
  .thumbnail .imgwrapper:hover {
    transform: scale(1.4) ; 
    position:relative;
    z-index:9999;
  }
  .thumbnail:hover .image_in_folder{
    display:block;
  }
  .thumbnail:hover .bi-star{
    transition: all 1s;
    opacity: 1;
  }
  .thumbnail .imgwrapper:hover .thumbinfo{
    z-index:99999;
  }
  .filterform:hover{
    opacity:0.9;
  }
 }

 @media (orientation: landscape) {
  .fullscreenmedia{
    max-width:100%;max-height:95vh;min-height:80vh;
  }
}

@media (orientation: portrait) {
  .fullscreenmedia{
    width:100%;    
  }
}

.filterform-wrapper{height:38px;}
.filterform{
  position:fixed;
  z-index:999980;
  opacity:0.5;
  background-color:white;
  width:100%;
  height:38px;
  padding-left:2.5vw;
}



    .dateinfo{
      padding:2px 2px 2px 10px;
    }
    .thumbnail {
      margin:1px;
      padding:0;
      float: left;
      position: relative;     
    }
   
    .thumbnail .imgwrapper{
      max_height:200px;
      transition: all 0.2s;
    }

    .videothumb{
      height:100%;
      width: 100%;
      background-repeat: no-repeat;
    }

    .thumbnail .image_in_folder{
      display:none;
      transition: all 0.2s;
    }
    


    .thumbinfo{
      position:absolute;
      z-index:998;
      color:#ff7;    
      opacity: 0.8;
      
    }
    .thumbinfofirst{
      margin-left:60%;
    }

    .bi-star-fill{
      opacity: 1;
    }
    .bi-star{
      opacity:0.1;
    }



    
    @keyframes imageani {
     100% { opacity: 1; }
    }
    img[lazy=loading] {
      opacity:0.2;
      animation: imageani .2s ease-in-out forwards;
    }
    img[lazy=error] {
    }
    img[lazy=loaded] {
    }


    .imgbox{
      width: 100%;
      height: 600px;
      position: relative;
    }
    .imgbox img{
	  	width: auto;
	    height: auto;
	    max-width: 100%;
	    max-height: 100%;
	    /* 向父元素定位 */
	    position: absolute;
      top:50%;
      left:50%;
      transform: translate(-50%,-50%);
    }
    .imgbox video{
	  	width: auto;
	    height: auto;
	    max-width: 100%;
	    max-height: 100%;
	    /* 向父元素定位 */
	    position: absolute;
      top:50%;
      left:50%;
      transform: translate(-50%,-50%);
    }

    body {
      overflow-y: scroll;
      overflow-x:hidden;
    }
    </style>


    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script>     
    window.photos=null;
    axios.post('api.php', {
    "m": 'photos'
    }).then(response => {
      window.photos=response.data;
      //console.log('photos', response.data)
    }, error => {
        console.log('photos错误', error.message)
    });
  </script>
   
  <!--script src="https://unpkg.com/vue@next"></script-->
  <script src="https://unpkg.com/vue@3.1.1/dist/vue.global.prod.js"></script>  
  <script src="https://unpkg.com/vue3-lazyload/dist/vue3-lazyload.min.js"></script>
  <script src='./js/common.js'></script>

  </head>
<body> 
<div id='tester' style='width:100%;height:2000px;'></div>
<script>
window.screenWidth = document.querySelector('#tester').offsetWidth;
window.screenHeight = window.innerHeight;
document.querySelector('#tester').parentNode.removeChild(document.querySelector('#tester'));
window.screenPad=window.innerWidth-document.querySelector('body').offsetWidth;
console.log(window.screenPad);
</script>
  <div id="app" style='width:100%;'>
    <photopreview :photos='thumbs' ref='pp'></photopreview>
    <div class='filterform-wrapper'>
      <form class="form-inline filterform" style=''>
        <div class="form-group">
          <label class="sr-only"  for="filter">Filter:</label>
          <input type="text" class="form-control form-control-sm" id="filter" v-model="image_filter" placeholder="Filter" style='width:19vw;'>
        </div>
        <div class="form-group"  >
          <label class="sr-only"  for="years">years:</label>    
          <select id='years' class="form-control custom-select-sm" required v-model="selectedYear" style='width:19vw;' >
            <option value="0">All</option>
            <option v-for="y in years" :value="y" key='y'>{{ y }}</option>
          </select>
        </div>
        <div class="form-group"  >
          <label class="sr-only"  for="dateformat">DateFormat:</label>    
          <select id='dateformat' class="form-control custom-select-sm" required v-model="dateformat" style='width:19vw;'>
            <option selected value="2">Day</option>
            <option value="1">Mon</option>
            <option value="0">Year</option>
          </select>
        </div>
        <div class="form-group">
          <label  class="sr-only"  for="thumb_height">Size:</label>    
          <select id='thumb_height' class="form-control custom-select-sm" required v-model="thumb_height" style='width:19vw;'>
            <option value="50">&nbsp;Tiny</option>
            <option value="100">Smaller</option>
            <option value="150">Small</option>
            <option value="200">Normal</option>
            <option value="250">Big</option>
            <option value="300">Bigger</option>
          </select>
        </div>
        <div class="form-group">
          <label  class="sr-only"  for="type">Type:</label>    
          <select id='type' class="form-control custom-select-sm" required v-model="mtype" style='width:19vw;'>
            <option value="a">Media</option>
            <option value="p">Photos</option>
            <option value="v">Videos</option>
          </select>
        </div>
      </form>
    </div>

     
    <vue-gallery :photos="thumbs" :starcb="star" @show-image='showImage'></vue-gallery>
    <div style='height:800px;width:100%;' ref="bottom"></div>    
  </div>
  <!--div class="modal fade" id="imgModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"  style="width:100%;height:100%">
    <div class="modal-dialog modal-xl" style="width:100%;height:100%">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Preview</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
<div class="imgbox">


</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Open</button>
        </div>
      </div>
    </div>
  </div-->
<script>

function bShowImg(path,isVideo){
  if(!isVideo){
    $('#imgModal').find('.imgbox').html("<img src='"+path+"'/>");
  }else{
    $('#imgModal').find('.imgbox').html("<video id='video1' controls><source src='"+path+"' type='video/mp4'></video>");
    document.getElementById("video1").play();
  }
  $('#imgModal').modal({});
  $('#imgModal').on('hide.bs.modal',
    function() {
      $('#imgModal').find('.imgbox').html('');
    })
}

const App = {
  components: {
  },
  data(){
    return {
      photos: null,
      thumb_height:this.ismobile()?100:200,
      _image_filter:'',
      _image_filter_timer:0,
      mtype:'a',
      dateformat:1, 
      years:null,
      selectedYear:new Date().getFullYear(),

      screenWidth: 0,
      screenHeight: 0,
      paddingWidth:2,
      maxScale:this.ismobile()?1.8:1.3,
      years:null,
      selectedYear:new Date().getFullYear(),
    };
  },
  watch: {
      selectedYear(newval, oldval) {
         window.scrollTo(0,0);
      },
      mtype(newval, oldval) {
         window.scrollTo(0,0);
      },
      dateformat(newval, oldval) {
         window.scrollTo(0,0);
      },
      _image_filter(newval, oldval) {
         window.scrollTo(0,0);
      }      
    },
  computed: {
    image_filter: {
      // getter
      get() {
        return this._image_filter;
      },
      // setter
      set(newValue) {
        clearTimeout(this._image_filter_timer);
        this._image_filter_timer=setTimeout(()=>{
          this._image_filter=newValue;
          this._image_filter_timer=0;
        },1000);
      }
    },

    // 计算属性的 getter
    thumbs() {
      var ret=[];
      var years=[];
      if(this.photos==null)return ret;
      var photos=this.photos.sort(function(a,b){
        var t=a[4]-b[4];
        if(t!=0)return t;
        return t;
      });
      var olddt=null,oldTs=0,lastthumb=null;
      var currentRow=[];
      var currentRowWidth=0;

      for(var i=0;i<photos.length;++i){
        var vshow=(this._image_filter==null || this._image_filter.length=='' || photos[i][6].indexOf(this._image_filter)!=-1);
        if(vshow && this.mtype=='p'){
          if(photos[i][3]>=4){
            vshow=false;
          }
        }
        if(vshow && this.mtype=='v'){
          if(photos[i][3]<4){
            vshow=false;
          }
        }

        var ts=photos[i][4]*1000;
        var dt=new Date(ts);
        var nowYear=dt.getFullYear();

        if(this.selectedYear!=0 && this.selectedYear!=nowYear){
          vshow=false;
        }        

        if(years.length==0||years[years.length-1]!=nowYear){
          years.push(nowYear);
        }

        if(!vshow)continue;
        var dt=formatDateX(dt,this.dateformat);       
        var ddate="";
        var collapse=false;
        var star=photos[i][5];
        var nframes=photos[i][7];
        if(!nframes>=1){
          nframes=1;
        }
        var pw=photos[i][1];
        var ph=photos[i][2];
        if(photos[i][3]==4){
          //if(nframes>1){
            pw=pw/10;
            //var nrows=Math.floor(nframes/10);
            ph=ph/2;
          //}
        }

        if(vshow){
          if(dt!=olddt){
            ddate=dt;
          }
          if(oldTs!=0 && (oldTs-ts)<5000 && -(oldTs-ts)<5000){//if size equal!!!
            if(pw==lastthumb.pw&&ph==lastthumb.ph){
              if(ret[ret.length-1].length<5){
                collapse=true;
              }
            }
            
          }
          olddt=dt;
          oldTs=ts;
        }
        
        var dimg={
          p:photos[i][6],
          t:photos[i][0],
          w:Math.floor(pw*this.thumb_height/ph),
          h:Math.floor(this.thumb_height),
          'pw':pw,
          'ph':ph,
          v:vshow,
          d:ddate,
          s:star,
          type:photos[i][3],
          nf:nframes,
          col:0,
          scale:1
        };
        if(dimg.type==4){
          dimg.vw=pw*this.thumb_height/ph*10;
          dimg.vh=this.thumb_height*2;
        }
        if(dimg.type>=4){
          collapse=false;
        }
        let starcount=0;
        if(ret.length>0){
          for(let ti=0;ti<ret[ret.length-1].length;++ti){
            if(ret[ret.length-1][ti].s){
            ++starcount;
            }
          }
        }
        if(starcount>=1&&dimg.s){
          collapse=false;
        }
        if(!collapse){
          ret.push([dimg]);  
          if(vshow){
            currentRowWidth+=dimg.w+this.paddingWidth;
            currentRow.push(ret[ret.length-1]);
          }
        }else{
          ret[ret.length-1].push(dimg);
        }
        lastthumb=dimg;
        
        if(currentRow.length>1 &&(ddate!="" || currentRowWidth>this.screenWidth)){
          let cw=currentRowWidth-(dimg.w+this.paddingWidth)-this.paddingWidth*(currentRow.length-1);
          let tw=this.screenWidth-this.paddingWidth*(currentRow.length-1);
          var scale=tw/cw;
          if(scale>this.maxScale)scale=this.maxScale;
          for(let tr=0;tr<(currentRow.length-1);++tr){
            let vtr=currentRow[tr];
            for(let tri=0;tri<vtr.length;++tri){
              let item=vtr[tri];
              item.w=Math.floor(item.w*scale);
              item.h=Math.floor(item.h*scale);
              item.col=tr;
              item.scale=Math.floor(scale*1000)/1000;
              /*if(dt== "2015-8-31"){
                console.log(item.w);
              }*/
            }           
          }
          currentRow=[currentRow[currentRow.length-1]];
          currentRowWidth=dimg.w+this.paddingWidth;
        }
      }
      if(currentRowWidth>0){
          let cw=currentRowWidth/*-(dimg.w+this.paddingWidth)*/-this.paddingWidth*(currentRow.length);
          let tw=this.screenWidth-this.paddingWidth*(currentRow.length);
          var scale=tw/cw;
          if(scale>this.maxScale)scale=this.maxScale;
          for(let tr=0;tr<(currentRow.length);++tr){
            let vtr=currentRow[tr];
            for(let tri=0;tri<vtr.length;++tri){
              let item=vtr[tri];
              item.w=Math.floor(item.w*scale);
              item.h=Math.floor(item.h*scale);
              item.col=tr;
              item.scale=Math.floor(scale*1000)/1000;
            }
          }           
      }
      for(var i=0;i<ret.length;++i){
        if(ret[i].length>1){
          ret[i]=ret[i].sort(function(a,b){
            return b.s-a.s;
          });
        }
      }
      window.scrollTo(0,0);
      if(this.years==null){
        this.years=years;
      }       
      return ret;
    }
  },
  created(){
    this.getimg();
    //this.screenWidth = document.body.clientWidth-20;
    //this.screenWidth = window.document.querySelector('#app').offsetWidth;
    this.screenWidth=window.screenWidth;
    this.screenHeight=window.screenHeight;
  },
  mounted() {
    var that=this;
    window.onresize = () => {
      that.screenWidth = window.document.querySelector('#app').offsetWidth;
      //that.screenWidth = document.body.clientWidth;
      that.screenHeight = window.innerHeight;
      //that.screenWidth=that.$refs.bottom.offsetWidth;
    };
    //setTimeout(()=>{this.screenWidth = window.document.querySelector('#app').offsetWidth;},3000);
  },
  methods: {
    star(path){
      var state=0;
      for(var i=0;i<this.photos.length;++i){
        if(this.photos[i][6]==path){
          this.photos[i][5]=!this.photos[i][5];
          state=this.photos[i][5];
          break;
        }  
      }
      axios.post('api.php', {
        "m": 'star',
        "path": path,
        'star':state?1:0
        }).then(response => {
            console.log('star', response.data)
        }, error => {
            console.log('star错误', error.message)
        });
    },
    getimg () {
        /*var that = this;
        axios.post('api.php', {
        "m": 'photos'
        }).then(response => {
          that.photos=response.data;
          //console.log('photos', response.data)
        }, error => {
            console.log('photos错误', error.message)
        });*/
        if(window.photos!=null && this.photos==null){
          this.photos=window.photos;
        }else{
          var that=this;
          setTimeout(() => {
            that.getimg();
          }, 1);
        }

    },
    showImage(i0,i1){
      this.$refs.pp.curIndex0=i0;
      this.$refs.pp.curIndex1=i1;
      this.$refs.pp.show=true;
    },
    ismobile(){
      var result = window.matchMedia("(max-width: 800px)").matches;
      return result;
    }
  }
};
const myapp=Vue.createApp(App);
myapp.use(VueLazyload);
myapp.component('videothumb', {
  props: ['photo'],
  data: function () {
    return {
      intvfunc:null,
      starttime:0,
      toffset:0,
      playInterval:400
    }
  },
  components: {
  },
  template: `<div 
              class='videothumb'
              @mouseover.native='startplay()'
              @mouseout.native='endplay()'
              :style="mstyle"
              >
              </div>`,
  computed: {
    mstyle() {
      var off=Math.floor(this.toffset/this.playInterval);
      off=off%this.photo.nf;
      var offy=Math.floor(off/10);
      var offx=Math.floor(off%10);
      var vx=-Math.floor(parseInt(this.photo.vw)/10*offx);
      var vy=-Math.floor(parseInt(this.photo.vh)/2*offy);
      var ret= {
        'background-image':'url('+this.photo.t+')',
        'background-size': this.photo.vw +'px '+this.photo.vh+'px',
        'background-position': vx +'px '+vy+'px'
      };
      //console.log(ret);
      return ret;
    }
  },
  methods: {
    startplay(){     
      this.starttime=Date.now();
      if(this.intvfunc!=null){
        return;
      }
      var that=this;
      //this.endplay();
      this.intvfunc=setInterval(function(){
        that.toffset=Date.now()-that.starttime;
      },this.playInterval);
    },
    endplay(){
      if( this.intvfunc!=null){
        clearInterval(this.intvfunc);
        this.intvfunc=null;
      }
      this.toffset=0;
    }
  },
  beforeUnmount() {
    this.endplay();
  }
});


myapp.component('photopreview', {
  props: ['photos'],
  data: function () {
    return {
      curIndex0:0,
      curIndex1:0,
      mshow:false,
      startX:0,//开始触摸的位置
      moveX:0,//滑动时的位置
      endX:0,//结束触摸的位置
      disX:0,//移动距离
      slideEffect:'',
    }
  },
  components: {
  },
  template: `<div v-if='show'
              style='position:fixed;width:100%;height:100%;z-index:999999;background-color:black;' @click='this.show=false'
              @touchstart='touchStart'
              @touchmove='touchMove'
              @touchend='touchEnd'
              >
                <div ref='ani' :style="slideEffect" >
                    <div style='transition:opacity 0.4s ease' v-for="(m, index) in media" :key="m.mediaSrc"  :style="{'opacity':opacity[index]}" >
                      <img v-if='m.isImg' :src='m.mediaSrc' class='fullscreenmedia'/>
                      <video v-if='!m.isImg' controls :src='m.mediaSrc' :poster='m.poster' class='fullscreenmedia'></video>
                    </div>                  
                </div>
              </div>
              <div v-if='show'
              style='position:fixed;width:250px;height:50px;top:calc(100% - 150px);left:calc(50% - 125px);z-index:9999999;background-color:white;' 
              >
                <div v-for="(m, index) in media1" :key="m.mediaSrc" style='width:50px;height:50px;float:left;' @click.stop='setImg(m.idx.i0,m.idx.i1)' >
                  <img :src='m.mediaSrc' style='width:50px;height:50px;' />
                </div>
              </div>
              `,
  computed: {
    media(){
      if(this.photos==null || this.photos.length==0){
        return [];
      }
      let ret=[];
      for(let off=0;off<2;++off){
        let idx=this.normalize(this.curIndex0,this.curIndex1+off);
        let cur=this.photos[idx.i0][idx.i1];
        let isImg=cur.type!=5;
        let src='';
        if(isImg){
          src= "getmediafile.php?pre=1&p="+encodeURIComponent(cur.p);
        }else{
          src= "getmediafile.php?pre=1&p="+encodeURIComponent(cur.p);
          if(off!=0){
            src='';
          }
        }
        
        let t={
          'isImg':isImg,
          'mediaSrc':src,
          'poster':cur.t
        };
        ret.push(t);
      }
      return ret;
    },
    media1(){
      if(this.photos==null || this.photos.length==0){
        return [];
      }
      let ret1=[];
      for(let off=-2;off<3;++off){
        let idx=this.normalize(this.curIndex0,this.curIndex1+off);
        let cur=this.photos[idx.i0][idx.i1];
        let src='';
        src= cur.t;
        let t={
          'mediaSrc':src,
          'idx':idx
        };
        ret1.push(t);
      }
      return ret1;
    },
    opacity(){
      let ret=[];
      let off=this.endX-this.startX;
      if(this.slideEffect ==''){
        off=0;
      }
      let tpos=0;
      let base=window.innerHeight;
      if(this.$refs.ani!=null)
        base=this.$refs.ani.children[0].offsetHeight;

      for(let i=0;i<this.media.length;++i){
        let diff=(tpos+off)/base;
        diff=1-Math.abs(diff);
        if(diff>1)diff=0;
        if(diff<0.1)diff=0.1;
        ret.push(diff);
        if(this.$refs.ani!=null)
        tpos+=this.$refs.ani.children[i].offsetHeight;
        //if(i==0)ret.push(1);
        //if(i>=1)ret.push(0.2);
      }
      return ret;
    },
    show: {
      get() {
        return this.mshow;
      },
      // setter
      set(newValue) {
        this.mshow=newValue;
        if(this.mshow){
          document.body.style.overflow='hidden';
          document.body.style['padding-right']=window.screenPad+'px';
        }else{
          document.body.style.overflow='scroll';
          document.body.style['padding-right']=0;
        }
      }
    }
  },
  methods: {
    normalize(i0,i1){
      while(i1<0||i1>this.photos[i0].length-1){
        if(i1<0){
          i0=i0-1;
          if(i0<0){
            i0+=this.photos.length;
          }
          i1+=this.photos[i0].length;
        }
        
        if(i1>this.photos[i0].length-1){
          i1-=this.photos[i0].length;
          i0=i0+1;
          if(i0>this.photos.length-1){
            i0-=this.photos.length;
          }
        }
       
      }
      return {'i0':i0,'i1':i1};
    },
    setImg(i0,i1){
      let idx=this.normalize(i0,i1);
      this.curIndex1=idx.i1;
      this.curIndex0=idx.i0;      
    },
    nextImg(){
      let i0=this.curIndex0;
      let i1= this.curIndex1+1;
      this.slideEffect ='transition:all 0.4s ease;transform:translateY('+(-this.$refs.ani.children[0].offsetHeight)+'px);';
      setTimeout(()=>{
        this.setImg(i0,i1);
        this.slideEffect ='';
      },400);      
    },
    prevImg(){
      let i0=this.curIndex0;
      let i1= this.curIndex1-1;
      this.setImg(i0,i1);
      //this.slideEffect ='transform:translateY('+(this.endY-this.startY-200)+'px);';
      setTimeout(()=>{
        this.slideEffect ='';
      },400);
      
    },
    touchStart:function(ev) {
      ev = ev || event;
      ev.preventDefault();
      if(ev.touches.length == 1) { //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
       this.startX = ev.touches[0].clientY; // 记录开始位置
       console.log(this.startX);
      }
     },
     touchMove:function(ev) {
      ev = ev || event;
      ev.preventDefault();     
      if(ev.touches.length == 1) { //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
        this.endX = ev.touches[0].clientY; // 记录开始位置
        this.slideEffect = 'transform:translateY('+(this.endX -this.startX)+'px)';
        //this.slideEffect = ' box-shadow: 0px 0px 10px #ee0a24 inset, 0px 0px 0px 15px #fff inset, 0px 0px 10px 15px #07c160 inset;';
        console.log(this.endX);
       }   
     },
    touchEnd:function(ev){
      ev = ev || event;      
      if(ev.changedTouches.length == 1) { //tounches类数组，等于1时表示此时有只有一只手指在触摸屏幕
      this.endX = ev.changedTouches[0].clientY; 
      console.log(this.endX);
      }
      if(this.endX-this.startX>20){
        this.prevImg();
        ev.preventDefault();
      }else       if(this.endX-this.startX<-20){         
        this.nextImg();
        ev.preventDefault();
      }else{
        this.show=false;
      }
    }
  },
  beforeUnmount() {
    this.show=false;
  }
});


myapp.component('vue-gallery', {
  props: ['photos','starcb'],
  data: function () {
    return {
      activePhoto: null,
    }
  },
  emits: ['showImage'],
  components: {
  },
  template: `
      <template v-for="(iphoto, iindex) in photos">
        <div class="clearfix" v-if="iphoto[0].d !== '' || iphoto[0].col==0"></div>
        <div class="dateinfo" v-if="iphoto[0].d !== ''">
              {{ iphoto[0].d }}
        </div>
        <div class="thumbnail"               
            :style="{width:iphoto[0].w+'px', height:iphoto[0].h+'px'}"
            v-show="iphoto[0].v">            
            <div v-for="(photo, index) in iphoto" :key="photo.t" class="imgwrapper"
                :class="{image_in_folder:index>0}"
                :style="{width:photo.w+'px', height:photo.h+'px'}"
                @click="showImg(photo,iindex,index)">
              <div class='thumbinfo' :class="{thumbinfofirst:photo.col==0}">
                <span v-if="photo.type>=4">V</span>
                <span v-if="iphoto.length>1 && index==0">{{iphoto.length}}</span>         
                <i class="thumbstar" :class="{'bi-star':!photo.s,'bi-star-fill':photo.s}" @click.stop="star(photo);"></i>
              </div>
              <img v-if="photo.type!=4"
                v-lazy="photo.t" :key="photo.t" 
                style='width:100%;height:100%'
              />
              <videothumb 
              v-if="photo.type==4"
              :photo="photo"
              />
              <!--
              <video  autoplay="autoplay"  v-if="photo.type==5" controls  :poster="photo.t" style='width:100%;height:100%' @mouseover.native='videopreview($event,photo.p)' @mouseout.native='stoppreview($event)'></video>
              -->
            </div>
        </div>
      </template>
      <div class="clearfix"></div>`,
  mounted () {
    this.changePhoto(0)
    document.addEventListener("keydown", (event) => {
      if (event.which == 37)
        this.previousPhoto()
      if (event.which == 39)
        this.nextPhoto()
    })
  },
  methods: {
    star(photo){
      photo.s=!photo.s;
      console.log(photo);
      this.starcb(photo.p);
    },
    changePhoto (index) {
      this.activePhoto = index
    },
    nextPhoto () {
      this.changePhoto( this.activePhoto+1 < this.photos.length ? this.activePhoto+1 : 0 )
    },
    previousPhoto () {
      this.changePhoto( this.activePhoto-1 >= 0 ? this.activePhoto-1 : this.photos.length-1 )
    },
    showImg(photo,i0,i1){

      /*if(photo.type<4){
        bShowImg("getmediafile.php?p="+encodeURIComponent(photo.p),photo.type>=4);
      }else{
        bShowImg("getvideopreview.php?p="+encodeURIComponent(photo.p),photo.type>=4);
      }*/
      this.$emit('showImage', i0,i1);
    },
    videopreview(e,p){
      el=e.target;
      el.src='getmediafile.php?pre=1&p='+encodeURIComponent(p);
      //el.play();

    },
    stoppreview(e,p){
      el=e.target;
      el.pause();
    }
  }
});

vm=myapp.mount("#app");

</script>



<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" defer crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" defer crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"  defer crossorigin="anonymous"></script>


  </body>
</html>