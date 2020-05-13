import { Pipe, PipeTransform } from '@angular/core';
import { User } from '../models/user.model'

@Pipe({
  name: 'userFilter'
})
export class UserPipe implements PipeTransform {

  transform(users: User[], text: string): User[] {
    if( text.length === 0) { return users; }

    text = text.toLocaleLowerCase();

    return users.filter( data => {
      return data.Fullname.toLocaleLowerCase().includes(text);
    });
  }

}
