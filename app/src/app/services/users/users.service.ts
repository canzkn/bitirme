import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';
import { User } from '../../models/user.model'

@Injectable({
  providedIn: 'root'
})
export class UsersService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Get All Users
  getAllUsers(auth, post_data) : Observable<User[]>
  {
    return this.http.post<User[]>(this.constantService.API_URL + 'users/get_all', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
