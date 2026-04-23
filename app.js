function byId(id){return document.getElementById(id)} // grab

function safe(value){ // safe text
  return String(value ?? "")
    .replace(/&/g,"&amp;")
    .replace(/</g,"&lt;")
    .replace(/>/g,"&gt;")
    .replace(/"/g,"&quot;")
    .replace(/'/g,"&#039;")
}

function youtubeId(url){ // video id
  url=String(url||"")
  let id=""
  if(url.includes("watch?v=")) id=url.split("watch?v=")[1].split("&")[0]
  else if(url.includes("youtu.be/")) id=url.split("youtu.be/")[1].split("?")[0]
  return /^[a-zA-Z0-9_-]{11}$/.test(id)?id:""
}

function isImage(url){ // image link
  return /\.(jpg|jpeg|png|gif|webp)(\?.*)?$/i.test(url||"")
}

function thumb(url){ // thumb
  let id=youtubeId(url)
  if(isImage(url)) return url
  if(id) return "https://img.youtube.com/vi/"+id+"/hqdefault.jpg"
  return "https://via.placeholder.com/400x200?text=Text+Tutorial"
}

function mediaHtml(url){ // media html
  let id=youtubeId(url)
  if(id) return '<iframe src="https://www.youtube.com/embed/'+id+'" allowfullscreen></iframe>'
  if(isImage(url)) return '<img src="'+safe(url)+'">'
  return '<div class="text-box">Text tutorial</div>'
}

function makeData(values){ // form data
  let data=new FormData()
  for(let key in values) data.append(key,values[key])
  return data
}

function goWithId(key,id,page){ // page id
  localStorage.setItem(key,id)
  window.location=page
}

function loadJson(url,done,fail){ // json load
  fetch(url).then(function(res){return res.json()}).then(done).catch(fail||function(){})
}

function showNav(){ // nav
  let box=byId("nav")
  let type=document.body.dataset.nav
  let active=document.body.dataset.active||""
  if(!box||!type) return

  let links={
    home:[
      ["auth.html","Sign In",""],
      ["auth.html","Admin Panel","","color:#777;"]
    ],
    user:[
      ["explore.html","Explore","explore"],
      ["upload.html","Upload","upload"],
      ["request_skill.html","Request Skill","request"],
      ["profile.html","Profile","profile"],
      ["logout.php","Logout",""]
    ],
    admin:[
      ["admin.html","Admin Panel","admin"],
      ["logout.php","Logout",""]
    ]
  }[type]||[]

  let html='<div class="navbar"><div class="logo">SkillShare</div><div class="nav">'
  links.forEach(function(link){
    let cls=active && link[2]===active?' class="active"':''
    let style=link[3]?' style="'+link[3]+'"':''
    html+='<a href="'+link[0]+'"'+cls+style+'>'+link[1]+'</a>'
  })
  box.innerHTML=html+"</div></div>"
}

document.addEventListener("DOMContentLoaded",showNav)
