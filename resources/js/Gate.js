export default class Gate{
  constructor(user){
    this.user = user;
  }

  isDeveloper(){
    return this.user.role_id === 1;
  }

  isAdministrator(){
    return this.user.role_id === 2;
  }

  isAdministrative(){
    return this.user.role_id === 3;
  }

  isUser(){
    return this.user.role_id === 4;
  }

  isAdministrativeorUser(){
    if(this.user.role_id === 3 || this.user.role_id === 4){
      return true;
    }
  }

  isDeveloperorAdministrator(){
    if(this.user.role_id === 1 || this.user.role_id === 2){
      return true;
    }
  }

  isActive(){
    return this.user.active === 1
  }

  activeUser(){
    return this.user
  }

  whatIsIt(){
    var data = JSON.parse(this.user.roles);
    var a = [];
    for (var i = 0; i < data.length; i++) {
      a.push(data[i]['id']);
    }
    return a;
  }

  ifHavePermisos(d){

    var data = JSON.parse(this.user.roles);
    var a = [];
    for (var b = 0; b < data.length; b++) {
      a.push(data[b]['id']);
    }

    var iguales=0;

    for(var i=0; i < d.length; i++)
    {
      for(var j=0;j<a.length;j++)
      {
        if(d[i]==a[j])
        iguales++;
      }
    }

    if (iguales > 0) {
      return true;
    }else {
      return false
    }

  }

  ifPermisionWeb(){
    var data = JSON.parse(this.user.roles);
    var a = 0;
    for (var b = 0; b < data.length; b++) {
      a += data[b]['id'];
    }
    return a;
  }
}
