import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Tag } from '../../../models/tag.model';
import { ConstantService } from '../../../services/constant/constant.service'
import { AuthenticationService } from '../../../services/authenticaton/authentication.service';
import { ProfileService } from '../../../services/profile/profile.service';
import { Router } from '@angular/router';
@Component({
  selector: 'app-interest',
  templateUrl: './interest.page.html',
  styleUrls: ['./interest.page.scss'],
})
export class InterestPage implements OnInit {

  tags : Tag[] = [];
  textFilter = '';
  selected : number[] = [];
  buttonDisable : boolean = true;
  constructor(
    private http : HttpClient,
    private constantService : ConstantService,
    private auth : AuthenticationService,
    private pService : ProfileService,
    private router: Router) { }

  ngOnInit() {
    this.http.get<Tag[]>(this.constantService.API_URL + 'tags').subscribe(res => {
      this.tags = res
    })
  }

  // search item
  filterText( event )
  {
    const text = event.target.value;
    this.textFilter = text;
  }

  // select an element
  select(TagID)
  {
    this.selected.push(TagID)

    if(this.selected.length > 0)
    {
      this.buttonDisable = false;
    }
  }

  // unselect an element
  unselect(TagID)
  {
    const index: number = this.selected.indexOf(TagID);
    if (index !== -1) {
        this.selected.splice(index, 1);
    }

    if(this.selected.length == 0)
    {
      this.buttonDisable = true;
    }
  }

  // element is selected?
  isSelect(TagID)
  {
    return this.selected.find(x => x == TagID)
  }

  // submit selected items
  submit()
  {
    if(this.selected.length > 0)
    {
      var postObj = Object.assign({}, this.selected);

      this.auth.userData$.subscribe(res => {
        this.pService.addInterest(res.data, JSON.stringify(postObj)).subscribe(response => {
          if(response.message == "ADD_INTEREST_SUCCESS")
          {
            this.router.navigate(['board/home']);
          }

          if(response.message == "AUTHORIZATION_FAILED")
          {
            this.auth.logout();
          }
        })
      })
    }

    return false;
  }
}
