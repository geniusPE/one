/**
 * Created by jim on 15-11-4.
 */

/**
 * API协议串生成器
 * @param int userId 用户ID
 * @param string sessionId 当前sessionid
 * @constructor
 */
function APIBuilder(userId, sessionId){
    this.sessionid=sessionId;
    this.data={
        count:0,
        source:0,
        cliname:"SBank",
        cliver:"1.0",
        uid:userId,
        screen:{
            minsize:1024,
            maxsize:768
        },
        reqs:[]
    };
    /**
     * 添加一个API
     * @param name API名字
     * @param version API版本
     * @param params API参数
     * @returns {APIBuilder}
     */
    this.add=function(name, version, params){
        this.data.reqs.push({
            "name":name,
            "version":version,
            "params":params
        });
        this.data.count=this.data.reqs.length;
        return this;
    };

    /**
     * 构建协议JSON，返回的是个JSON对象
     * @returns {*}
     */
    this.build=function(){
        result=JSON.stringify(this.data);
        return result;
    };

    /**
     * 构建可以直接用于提交的请求串，其中包含jsessionid参数
     * @returns {string}
     */
    this.getData=function(){
        var result="";
        if (this.sessionid!=null && this.sessionid!=undefined){
            result="jsessionid="+this.sessionid+"&";
        }
        result+=("c="+this.build());
        return result;
    }
}

/**
 * API解析器，专门哦你过来解析服务器返回的APIi响应结果
 * @param response
 * @constructor
 */
function APIParser(response){
    this.response=response;
    /**
     * 根据索引从响应结果中提取API返回结果
     * @param index 索引，如果越界会返回undefined
     * @returns {*}
     */
    this.byIndex=function(index){
        var result=this.response.resps[index];
        return result;
    };

    /**
     * 根据名字和版本号从A响应结果中获取指定的API反馈结果
     * @param string name api名字
     * @param string version api版本
     * @return {*}
     */
    this.byName=function(name, version){
        var cnt=this.response.resps.length;
        for(var i=0; i<=cnt-1; i++){
            var t=this.response.resps[i];
            if ( (t.name==name) && (t.version==version) ){
                return t;
            }
        }
        return null;
    };
}

//var apiBuilder=new APIBuilder(15, "/api");
//var json=apiBuilder.add("user.profile.info.get", "2.0", {userid:15, pp:123})
//.add("user.abc.abc.post", "1.0", {len:10, aaa:"20"})
//.build();
//
//console.log(json);