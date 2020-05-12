import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { ConstantService } from '../../services/constant/constant.service'
import { Observable } from 'rxjs';
import { Tags } from '../../models/tags.model';
@Injectable({
  providedIn: 'root'
})
export class TagsService {

  constructor(
    private constantService: ConstantService,
    private http: HttpClient,
  ) { }

  // Get All Tags
  getAllTags(auth, post_data) : Observable<Tags[]>
  {
    return this.http.post<Tags[]>(this.constantService.API_URL + 'tag/all', post_data, {
      headers: {'Authorization': 'UserID='+ auth.UserID +'; token='+ auth.Token +';'}
    })
  }
}
