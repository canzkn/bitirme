import { Component, OnInit } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Tag } from '../../../models/tag.model';
import { ConstantService } from '../../../services/constant/constant.service'
@Component({
  selector: 'app-interest',
  templateUrl: './interest.page.html',
  styleUrls: ['./interest.page.scss'],
})
export class InterestPage implements OnInit {

  tags : Tag[] = [];
  textFilter = '';

  constructor(
    private http : HttpClient,
    private constantService : ConstantService) { }

  ngOnInit() {
    this.http.get<Tag[]>(this.constantService.API_URL + 'tags').subscribe(res => {
      this.tags = res
    })
  }

  filterText( event )
  {
    const text = event.target.value;
    this.textFilter = text;
  }
}
