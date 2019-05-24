
let user = window.App.user;


module.exports = {
  updateReply(reply){
    return reply.user_id === user.id
  },
  canMarkBest(reply){
    //return reply.thread.user_id === user.id;
    return reply.canMarkBest;
    //return reply.canMarkBest;
  }
};
