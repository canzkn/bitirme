import { async, ComponentFixture, TestBed } from '@angular/core/testing';
import { IonicModule } from '@ionic/angular';

import { GroupMessagePage } from './group-message.page';

describe('GroupMessagePage', () => {
  let component: GroupMessagePage;
  let fixture: ComponentFixture<GroupMessagePage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ GroupMessagePage ],
      imports: [IonicModule.forRoot()]
    }).compileComponents();

    fixture = TestBed.createComponent(GroupMessagePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  }));

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
