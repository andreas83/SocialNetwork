export function getThumbnail(avatar, width, height){
    return avatar.replace("storage", "resize")+"?w="+width+"&h="+height+"&fit=crop&fm=pjpg";
}
