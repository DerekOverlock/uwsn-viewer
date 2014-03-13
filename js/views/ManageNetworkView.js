var app = app || {};

app.ManageNetworkView = Backbone.View.extend({
    initialize: function() {
        this.render();
        this.listenTo(this.model, 'change', this.render);
        this.model.fetch();
    },
    template: function(optViewParam) {
        var param = optViewParam || {};
        return Mustache.render(app.TemplateManager.getTemplate('manage_network'), param);
    },
    events: {
        'click button' : 'save'
    },
    save: function(event) {
        var form = $(event.target).closest("form");
        alert('Coming soon...');
    },
    render: function() {
        this.$el.html(
            this.template(
                this.model.toJSON()
            )
        );
    }
});

app.Models = app.Models || {};
app.Models.NodeNetwork = Backbone.Model.extend({
    initialize: function(attr, options) {

    },
    url: function() {
        return this.urlRoot + '?id='+this.id;
    },
    urlRoot: 'scripts/getNetwork.php'
});