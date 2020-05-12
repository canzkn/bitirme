import { NgModule } from '@angular/core';
import { TagPipe } from './tag.pipe';
import { TagsPipe } from './tags.pipe'



@NgModule({
  declarations: [TagPipe, TagsPipe],
  exports: [
    TagPipe,
    TagsPipe
  ]
})
export class PipesModule { }