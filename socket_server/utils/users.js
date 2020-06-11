const users = [];

// Join user to chat
function userJoin(id, UserID, GroupID) {
  const user = { id, UserID, GroupID };

  users.push(user);

  return user;
}

// Get current user
function getCurrentUser(id) {
    return users.find(user => user.id === id);
}

// User leaves chat
function userLeave(id) {
    const index = users.findIndex(user => user.id === id);

    if (index !== -1) {
        return users.splice(index, 1)[0];
    }
}
  
// Get Group users
function getGroupUsers(GroupID) {
    return users.filter(user => user.GroupID === GroupID);
}

module.exports = {
    userJoin,
    getCurrentUser,
    userLeave,
    getGroupUsers
};