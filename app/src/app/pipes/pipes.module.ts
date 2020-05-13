import { NgModule } from '@angular/core';
import { TagPipe } from './tag.pipe';
import { TagsPipe } from './tags.pipe';
import { UserPipe } from './user.pipe'



@NgModule({
  declarations: [TagPipe, TagsPipe, UserPipe],
  exports: [
    TagPipe,
    TagsPipe,
    UserPipe
  ]
})
export class PipesModule { }