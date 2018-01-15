const AV=require('../lib/av-weapp-min');

class Todo extends AV.Object{

    get TodoOwner(){return this.get('TodoOwner');}
    set TodoOwner(value){this.set('TodoOwner',value);}

    get TodoName(){
        return this.get('TodoName');
    }
    set TodoName(value){
        this.set('TodoName',value);
    }
    get TodoCon(){
        return this.get('TodoCon');
    }
    set TodoCon(value){
        this.set('TodoCon',value)
    }
     get TodoStart(){return this.get('TodoStart');}
    set TodoStart(value){this.set('TodoStart',value);}

     get TodoEnd(){return this.get('TodoEnd');}
    set TodoEnd(value){this.set('TodoEnd',value);}

     get TodoMember(){return this.get('TodoMember');}
    set TodoMember(value){this.set('TodoMember',value);}
}

AV.Object.register(Todo,'Todo');
module.exports=Todo;